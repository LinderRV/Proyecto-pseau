<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class GeminiController extends Controller
{
    /**
     * Envía un mensaje al modelo Gemini 1.5 (Google Generative AI)
     * usando la API oficial y devuelve el texto de respuesta.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $message = $request->input('message') ?? '';
        $imageUrl = null; // URL used by the local UI (may be localhost)
        $modelImageUrl = null; // URL sent to the external AI service (must be publicly reachable)

        // If an image was uploaded, store it and make it accessible via storage
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = Storage::disk('public')->putFile('ai-chat-images', $file);
            if ($path) {
                // asset('storage/...') works since storage:link is created
                $imageUrl = asset('storage/' . $path);

                // Build a public URL for the AI model to fetch the image if a public base is configured.
                // By default we DO NOT send the local `asset()` URL to the external API because
                // it will often be `http://localhost...` and not reachable from Google servers.
                $publicBase = env('GEMINI_PUBLIC_BASE_URL') ?: env('APP_PUBLIC_URL') ?: env('APP_URL');
                if (!empty($publicBase)) {
                    // If the developer provided a public base (for example an ngrok URL), use it.
                    // Ensure no trailing slash
                    $publicBase = rtrim($publicBase, '/');
                    // Build model-facing URL: {publicBase}/storage/{path}
                    $modelImageUrl = $publicBase . '/storage/' . ltrim($path, '/');
                    // Ignore obvious localhost bases
                    if (str_contains($modelImageUrl, 'localhost') || str_contains($modelImageUrl, '127.0.0.1')) {
                        $modelImageUrl = null;
                    }
                }
            }
        }

        // Require at least message or image
        if (empty($message) && empty($imageUrl)) {
            return response()->json(['error' => 'Envía un mensaje o adjunta una imagen.'], 422);
        }
        $apiKey  = env('GEMINI_API_KEY');
        $apiUrl  = env('GEMINI_API_URL');

        if (empty($apiKey)) {
            return response()->json(['error' => 'Falta GEMINI_API_KEY en el archivo .env'], 500);
        }

        // ✅ Usa el endpoint moderno (v1beta) y el modelo actual
        if (empty($apiUrl)) {
            $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';
        }

        try {
            $client = new Client(['timeout' => 30]);

            $url = $apiUrl . (str_contains($apiUrl, '?') ? '&' : '?') . 'key=' . urlencode($apiKey);

            // Build payload. If image exists, include image URL in the prompt as an extra part.
            $parts = [];
            if (!empty($message)) {
                $parts[] = ['text' => $message];
            }
            if (!empty($imageUrl)) {
                if (!empty($modelImageUrl)) {
                    // If we were able to construct a publicly reachable URL, include it so the model
                    // can try to fetch/see the image.
                    $parts[] = ['text' => "[Image attached] {$modelImageUrl}"];
                } else {
                    // Otherwise include a safe placeholder and filename — do NOT expose a localhost URL to the AI.
                    $filename = $file->getClientOriginalName();
                    $parts[] = ['text' => "[Image attached: {$filename}]"];
                    // IMPORTANT: Tell the model that it cannot access the image and must not claim to have seen it.
                    // This prevents replies like "Ya la he recibido" when the model can't fetch localhost URLs.
                    $parts[] = ['text' => "NOTE_FOR_MODEL: The attached image '{$filename}' is NOT publicly accessible from the internet. Do NOT state or imply that you can view, analyze, or describe the image. Instead, ask the user for a public URL (for example an ngrok URL) or ask the user to describe the important parts they want help with. If the user asks for transcription, ask them to paste the text or provide a public link. Respond politely and request clarifying information."];
                    // Also add a note for debugging (not returned to the user, but logged) when running locally
                    Log::info('AI image uploaded but no public base configured; not sending localhost URL to Gemini. Set GEMINI_PUBLIC_BASE_URL if you want the model to access images.');
                }
            }

            $payload = [
                'contents' => [
                    ['parts' => $parts]
                ]
            ];

            $res  = $client->post($url, ['json' => $payload]);
            $body = json_decode((string)$res->getBody(), true);

            // ✅ Extraer respuesta de la estructura nueva
            $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? 'Sin respuesta.';

            // Save conversation to cache for the authenticated user
            try {
                $userId = Auth::id();
                if ($userId) {
                    $key = "ai_chat:user:{$userId}";
                    $messages = Cache::get($key, []);
                    // Append user message and AI reply
                    if (!empty($message) || !empty($imageUrl)) {
                        $messages[] = ['role' => 'user', 'text' => $message, 'imageUrl' => $imageUrl ?? null, 'created_at' => now()->toIso8601String()];
                    }
                    $messages[] = ['role' => 'ai', 'text' => $text, 'imageUrl' => null, 'created_at' => now()->toIso8601String()];
                    // Keep only last 200 messages
                    if (count($messages) > 200) {
                        $messages = array_slice($messages, -200);
                    }
                    Cache::put($key, $messages, now()->addDays(7));
                }
            } catch (\Exception $e) {
                Log::warning('Unable to cache ai chat: ' . $e->getMessage());
            }

            return response()->json(['reply' => $text]);
        } catch (ClientException $e) {
            $resp  = $e->getResponse();
            $status = $resp ? $resp->getStatusCode() : 'n/a';
            $body   = $resp ? (string)$resp->getBody() : '';
            Log::error("Gemini client error {$status}: {$body}");

            return response()->json([
                'error' => "Error del servicio Gemini ({$status}). Verifica tu API key o modelo. Respuesta: " .
                    substr($body, 0, 300)
            ], 502);
        } catch (RequestException $e) {
            Log::error('Gemini network error: ' . $e->getMessage());
            return response()->json(['error' => 'Error de red al contactar Gemini.'], 502);
        } catch (\Exception $e) {
            Log::error('Gemini general error: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado al contactar Gemini.'], 500);
        }
    }

    /**
     * Return cached chat history for the authenticated user.
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['messages' => []]);
        }
        $key = "ai_chat:user:{$userId}";
        $messages = Cache::get($key, []);
        return response()->json(['messages' => $messages]);
    }

    /**
     * Clear cached chat history for the authenticated user.
     */
    public function clear(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['ok' => false, 'message' => 'Not authenticated'], 401);
        }
        $key = "ai_chat:user:{$userId}";
        try {
            Cache::forget($key);
            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::warning('Failed to clear AI chat cache: ' . $e->getMessage());
            return response()->json(['ok' => false, 'message' => 'Unable to clear chat cache'], 500);
        }
    }
}

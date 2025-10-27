<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LearningController extends Controller
{
    /**
     * Mostrar la página principal de aprendizaje con todos los cursos
     */
    public function index()
    {
        $courses = \App\Models\Course::all();
        return view('learning.index', compact('courses'));
    }
    
    /**
     * Mostrar los problemas/ejercicios de un curso específico
     */
    public function course($courseId)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        
        // Obtener problemas con solución en video
        $problems = \App\Models\Question::where('course_id', $courseId)
            ->where('is_problem_solving', true)
            ->whereNotNull('video_url')
            ->where('video_url', '<>', '')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('learning.course', compact('course', 'problems'));
    }
    
    /**
     * Mostrar un problema específico con su video explicativo
     */
    public function problem($courseId, $problemId)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        $problem = \App\Models\Question::where('course_id', $courseId)
            ->where('id', $problemId)
            ->where('is_problem_solving', true)
            ->firstOrFail();
            
        // Obtener apuntes del usuario para este problema
        $notes = \App\Models\UserNote::where('user_id', auth()->id())
            ->where('question_id', $problemId)
            ->get();
            
        return view('learning.problem', compact('course', 'problem', 'notes'));
    }
    
    /**
     * Guardar un apunte para un problema
     */
    public function saveNote(Request $request, $courseId, $problemId)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120', // max 5MB
        ]);

        $data = [
            'user_id' => auth()->id(),
            'question_id' => $problemId,
            'course_id' => $courseId,
            'content' => $request->content,
            'title' => $request->title ?? 'Apunte ' . now()->format('d/m/Y H:i'),
        ];

        // Handle uploaded image if present
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('user_notes', 'public');
            $data['image_path'] = $path;
        }

        $note = \App\Models\UserNote::create($data);
        
        // Si la petición espera JSON (AJAX), devolvemos la nota creada
        if ($request->wantsJson() || $request->ajax()) {
            $noteArray = $note->toArray();
            $noteArray['image_url'] = $note->image_url;

            return response()->json([
                'success' => true,
                'message' => 'Apunte guardado correctamente.',
                'note' => $noteArray,
            ]);
        }

        return redirect()->back()->with('success', 'Apunte guardado correctamente.');
    }
    
    /**
     * Eliminar un apunte
     */
    public function deleteNote($noteId)
    {
        $note = \App\Models\UserNote::where('user_id', auth()->id())
            ->where('id', $noteId)
            ->firstOrFail();
            
        // delete stored image if exists
        if (!empty($note->image_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($note->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($note->image_path);
        }

        $note->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Apunte eliminado correctamente.',
            ]);
        }

        return redirect()->back()->with('success', 'Apunte eliminado correctamente.');
    }
}

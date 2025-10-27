<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirecciona al usuario a la página de autenticación de Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Obtiene la información del usuario desde Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            // Buscar o crear un usuario basado en el ID de Google
            $finduser = User::where('google_id', $user->id)->first();
            
            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                // Crear un nuevo usuario si no existe
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'email_verified_at' => now(), // Marcamos el email como verificado
                    'password' => bcrypt(rand(100000, 999999)) // Contraseña aleatoria
                ]);
                
                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (\Exception $e) {
            return redirect('login')->withErrors(['error' => 'Ocurrió un error al iniciar sesión con Google. Por favor intenta nuevamente.']);
        }
    }
}
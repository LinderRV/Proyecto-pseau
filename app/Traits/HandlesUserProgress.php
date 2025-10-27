<?php

namespace App\Traits;

use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;

trait HandlesUserProgress
{
    protected function saveProgress($type, $state, $answers = null)
    {
        $user = Auth::user();
        
        // Buscar progreso existente no completado
        $progress = UserProgress::where('user_id', $user->id)
            ->where('type', $type)
            ->where('completed', false)
            ->first();
            
        if (!$progress) {
            // Crear nuevo progreso
            $progress = new UserProgress([
                'user_id' => $user->id,
                'type' => $type,
                'started_at' => now(),
            ]);
        }
        
        $progress->current_state = $state;
        $progress->answers = $answers;
        $progress->last_activity_at = now();
        $progress->save();
        
        return $progress;
    }
    
    protected function getProgress($type)
    {
        $user = Auth::user();
        
        return UserProgress::where('user_id', $user->id)
            ->where('type', $type)
            ->where('completed', false)
            ->where('last_activity_at', '>=', now()->subHours(24))
            ->first();
    }
    
    protected function completeProgress($type)
    {
        $user = Auth::user();
        
        UserProgress::where('user_id', $user->id)
            ->where('type', $type)
            ->where('completed', false)
            ->update(['completed' => true]);
    }
    
    protected function clearOldProgress()
    {
        // Limpiar progreso antiguo (más de 24 horas)
        UserProgress::where('last_activity_at', '<', now()->subHours(24))
            ->where('completed', false)
            ->delete();
    }
}
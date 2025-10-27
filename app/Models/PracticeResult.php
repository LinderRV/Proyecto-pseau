<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'course_id',
        'question_id',
        'total_questions',
        'correct_answers',
        'score',
        'time_taken',
        'question_details',
        'is_correct',
    ];
    
    protected $casts = [
        'question_details' => 'array',
        'score' => 'float',
    ];
    
    /**
     * Get the question details attribute with custom handling for malformed JSON.
     */
    public function getQuestionDetailsAttribute($value)
    {
        // Si ya es un array, simplemente devolverlo
        if (is_array($value)) {
            return $value;
        }
        
        if (is_string($value)) {
            // Primero intentamos con un JSON normal
            $decoded = json_decode($value, true);
            
            // Si eso falla, probamos diferentes enfoques
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fix double-quoted JSON (a string that begins and ends with quotes)
                if (substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
                    $trimmedValue = substr($value, 1, -1);
                    $decoded = json_decode($trimmedValue, true);
                }
                
                // Si sigue fallando, intentamos con escapes adicionales
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $escapedValue = str_replace('\\', '', $value);
                    $decoded = json_decode($escapedValue, true);
                    
                    // Último intento con reemplazo de caracteres problemáticos
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $cleanedValue = preg_replace('/[[:cntrl:]]/', '', $escapedValue);
                        $decoded = json_decode($cleanedValue, true);
                    }
                }
            }
            
            // Si finalmente tenemos un resultado válido, lo devolvemos
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            } else {
                \Log::error('Error parsing question details', [
                    'error' => json_last_error_msg(),
                    'value_sample' => substr($value, 0, 100)
                ]);
            }
        }
        
        // Si todo falla, devolvemos un array vacío para evitar errores
        return [];
    }
    
    /**
     * Get the user that did the practice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the course related to this practice result.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the question related to this practice result.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
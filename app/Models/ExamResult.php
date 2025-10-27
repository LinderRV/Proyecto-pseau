<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'university_id',
        'total_questions',
        'correct_answers',
        'incorrect_answers',
        'unanswered',
        'score',
        'time_taken',
        'question_details',
    ];
    
    protected $casts = [
        'question_details' => 'array',
        'score' => 'float',
    ];
    
    /**
     * Get the user that took the exam.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the university related to this exam result.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
}

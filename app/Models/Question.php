<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question_text',
        'problem_statement',
        'course_id',
        'difficulty_level',
        'is_problem_solving',
        'explanation',
        'video_url',
        'university_id',
        'question_type',
        'image',
    ];
    
    /**
     * Get the course this question belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    /**
     * Get the university this question is related to.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
    
    /**
     * Get the options for this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class)->inRandomOrder();
    }
    
    /**
     * Get the correct option for this question.
     */
    public function correctOption()
    {
        return $this->options()->where('is_correct', true)->first();
    }
    
    /**
     * Get all user notes for this question
     */
    public function notes(): HasMany
    {
        return $this->hasMany(UserNote::class);
    }
    
    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeIdAttribute()
    {
        if (empty($this->video_url)) {
            return null;
        }
        
        $pattern = 
            '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
            
        preg_match($pattern, $this->video_url, $matches);
        
        if (isset($matches[2]) && strlen($matches[2]) == 11) {
            return $matches[2];
        }
        
        return null;
    }
    
    /**
     * Check if the question has a valid YouTube URL
     */
    public function hasVideo()
    {
        return !empty($this->getYoutubeIdAttribute());
    }
    
    /**
     * Get all practice results for this question.
     */
    public function practiceResults(): HasMany
    {
        return $this->hasMany(PracticeResult::class);
    }
}

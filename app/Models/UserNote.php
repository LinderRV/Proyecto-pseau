<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'user_id',
        'question_id',
        'course_id',
        'content',
        'title',
        'image_path',
    ];
    
    /**
     * Get the user that owns this note
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the question this note is related to
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
    /**
     * Get the course this note is related to
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get full URL for stored image if available
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) return null;
        return asset('storage/' . ltrim($this->image_path, '/'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'difficulty_level',
    ];
    
    /**
     * Get the careers this course is related to.
     */
    public function careers(): BelongsToMany
    {
        return $this->belongsToMany(Career::class)
            ->withPivot('importance')
            ->withTimestamps();
    }
    
    /**
     * Get the questions for this course.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}

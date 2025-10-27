<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Career extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
    ];
    
    /**
     * Get the courses associated with this career.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('importance')
            ->withTimestamps();
    }
    
    /**
     * Get the users studying this career.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the university this career belongs to (if any).
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id');
    }
}

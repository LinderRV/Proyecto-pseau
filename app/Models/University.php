<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'location',
        'description',
        'logo',
    ];
    
    /**
     * Get the users associated with this university.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Get the questions associated with this university.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}

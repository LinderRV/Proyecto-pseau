<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'current_state',
        'answers',
        'started_at',
        'last_activity_at',
        'completed'
    ];

    protected $casts = [
        'current_state' => 'array',
        'answers' => 'array',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

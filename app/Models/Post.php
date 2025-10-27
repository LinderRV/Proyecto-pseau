<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'published',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user that authored the post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now())
                     ->latest('published_at');
    }
}

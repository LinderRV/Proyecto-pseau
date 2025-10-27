<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'university_id',
        'career_id',
        'first_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'first_login' => 'boolean',
        ];
    }
    
    /**
     * Get the university the user is applying to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }
    
    /**
     * Get the career the user is studying for.
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }
    
    /**
     * Get all exam results for this user.
     */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
    
    /**
     * Get all practice results for this user.
     */
    public function practiceResults()
    {
        return $this->hasMany(PracticeResult::class);
    }
    
    /**
     * Get all posts authored by this user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * Check if the user has a specific role
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
    
    /**
     * Check if the user is an admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}

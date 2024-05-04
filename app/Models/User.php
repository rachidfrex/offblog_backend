<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the blogs that the user has liked.
     */
    public function likedBlogs()
    {
        return $this->belongsToMany(Blog::class, 'likes');
    }

    /**
     * Determine if the user has liked the given blog.
     */
    public function hasLiked(Blog $blog)
    {
        return $this->likedBlogs()->where('blog_id', $blog->id)->exists();
    }

    /**
     * Like the given blog.
     */
    public function like(Blog $blog)
    {
        return $this->likedBlogs()->attach($blog->id);
    }

    /**
     * Unlike the given blog.
     */
    public function unlike(Blog $blog)
    {
        return $this->likedBlogs()->detach($blog->id);
    }
}
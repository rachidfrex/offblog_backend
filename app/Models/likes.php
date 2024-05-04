<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'blog_id',
        'user_id',
    ];

    /**
     * Get the blog that the like belongs to.
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * Get the user that the like belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
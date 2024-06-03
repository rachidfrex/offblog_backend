<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image_url',
        'user_id',
        'category_id',
        'likes_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
    // return $this->belongsToMany(Category::class);
    return $this->belongsToMany(Category::class, 'blog_category');
    }
        public function blogs()
    {
        return $this->belongsToMany(Blog::class);
    }
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }
}

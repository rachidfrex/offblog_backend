<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog; 

class BlogController extends Controller
{
    public function createBlog(Request $req)
    {
        $blog = new Blog;
        $blog->title = $req->input('title');
        $blog->content = $req->input('content');
        $blog->image_url = $req->input('image_url');
        $blog->user_id = $req->input('user_id');
        $blog->category_id = $req->input('category_id');
        // set likes_count to 0 be default
        $blog->likes_count = 0;
      
        $blog->save();
        if ($blog != null) {
            return response()->json(['success' => 'Blog created successfully'], 201);
        } else {
            return response()->json(['error' => 'Blog creation failed'], 400);
        }
    }

    public function getBlogs()
    {
        $blogs = Blog::all();
        if ($blogs->count() > 0) {
            return response()->json($blogs, 200);
        } else {
            return response()->json(['error' => 'Blogs not found'], 404);
        }
    }

    public function getBlog($id)
    {
        $blog = Blog::find($id);
        if ($blog) {
            return response()->json($blog, 200);
        } else {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }

    public function updateBlog(Request $req, $id)
    {
        $blog = Blog::find($id);
        if ($blog != null) {
            $blog->title = $req->input('title');
            $blog->content = $req->input('content');
            $blog->image_url = $req->input('image_url');
            $blog->user_id = $req->input('user_id');
            $blog->category_id = $req->input('category_id');
            $blog->likes_count = $req->input('likes_count');
            $blog->save();
            return response()->json(['success' => 'Blog updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }

    public function deleteBlog($id)
    {
        $blog = Blog::find($id);
        if ($blog != null) {
            $blog->delete();
            return response()->json(['success' => 'Blog deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }
}
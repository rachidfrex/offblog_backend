<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Blog;
use App\Models\Category;
use App\Models\User;

use Illuminate\Support\Facades\Storage;



class BlogController extends Controller
{

public function createBlog(Request $req)
{
    $response = [];
    $blog = new Blog;
    $blog->title = $req->post('title');
    $blog->content = $req->post('content');
    $blog->user_id = $req->post('user_id');

    // set likes_count to 0 be default
    $blog->likes_count = 0;
    error_log(print_r($blog, true));
    
    if($req->hasFile('image_url')) {
        // Validate the uploaded file
        $req->validate([
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        $image = $req->file('image_url');
        $name = Str::slug($req->post('title')).'_'.time();
        $extension = $image->getClientOriginalExtension();
        $fileName = "{$name}.{$extension}";
        $image->storeAs('images', $fileName, 'public');
        $blog->image_url = Storage::url("images/{$fileName}");
    }

    // Find the categories by name and get their IDs
    $categoryNames = json_decode($req->post('categories'));

    $categories = Category::whereIn('name', $categoryNames)->get();

    if (count($categoryNames) != count($categories)) {
        return response()->json(['error' => 'One of the categories does not exist']);
    }
    $blog->save();

    // Associate the blog with the categories
    $blog->categories()->sync($categories->pluck('id'));

    if ($blog != null) {
        $response = [
            'success' => 'Blog created successfully',
            'blog' => $blog
        ];
        return response()->json($response, 201);
    } else {
        return response()->json(['error' => 'Blog creation failed'], 500);
    }
}

 
// get all the blogs
    public function getBlogs()
    {
        $blogs = Blog::with(['categories', 'user'])->get();
        if ($blogs->count() > 0) {
            return response()->json($blogs, 200);
        } else {
            return response()->json(['error' => 'Blogs not found'], 404);
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
        // get the user blog by id

        public function getUserBlogs($user_id)
        {
            $blogs = Blog::where('user_id', $user_id)->get();
            if ($blogs) {
                return response()->json($blogs, 200);
            } else {
                return response()->json(['error' => 'Blogs not found'], 404);
            }

        }
        // get the blog by id
        public function getBlog($id)
            {
                $blog = Blog::find($id);
                if ($blog) {
                    $blog->load(['categories', 'user']);
                    return response()->json($blog, 200);
                } else {
                    return response()->json(['error' => 'Blog not found'], 404);
                }
            }
    // delete the blog by id
                public function deleteBlog($id)
            {
                $blog = Blog::find($id);
                if ($blog != null) {
                    // Delete the image file
                    if ($blog->image_path != null) {
                        Storage::delete($blog->image_path);
                    }

                    $blog->delete();
                    return response()->json(['success' => 'Blog deleted successfully'], 200);
                } else {
                    return response()->json(['error' => 'Blog not found'], 404);
                }
            }

            // like the blog
            public function toggleLike(Request $request, $id)
            {
                $user = $request->user(); // Get the currently authenticated user
                $blog = Blog::find($id);

                if (!$blog) {
                    return response()->json(['error' => 'Blog not found'], 404);
                }

                if ($user->hasLiked($blog)) {
                    $blog->likes_count--;
                    $user->unlike($blog);
                } else {
                    $blog->likes_count++;
                    $user->like($blog);
                }

                $blog->save();

                return response()->json($blog, 200);
            }
            // get the blogs of a catrgory   
            
            
            
                // public function getBlogsByCategory($categoryId)
                // {
                //     $category = Category::find($categoryId);
                //     if (!$category) {
                //         return response()->json(['error' => 'Category not found'], 404);
                //     }

                //     $blogs = Blog::whereHas('categories', function ($query) use ($categoryId) {
                //         $query->where('id', $categoryId);
                //     })->get();

                //     return response()->json($blogs, 200);
                // }
                public function getBlogsByCategory($categoryId)
                {
                    $blogs = Blog::whereHas('categories', function ($query) use ($categoryId) {
                        $query->where('categories.id', $categoryId);
                    })->get();

                    return response()->json($blogs, 200);
                }



}
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
    // create a blog
public function createBlog(Request $req)
{
    $response = [];
    $blog = new Blog;
    $blog->title = $req->input('title');
    $blog->content = $req->input('content');
    $blog->user_id = $req->input('user_id');

    // set likes_count to 0 be default
    $blog->likes_count = 0;
    error_log(print_r($blog, true));
    
    if($req->hasFile('image_url')) {
        // Validate the uploaded file
        $req->validate([
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = $req->file('image_url');
        $name = Str::slug($req->input('title')).'_'.time();
        $extension = $image->getClientOriginalExtension();
        $fileName = "{$name}.{$extension}";
        $image->storeAs('images', $fileName, 'public');
        $blog->image_url = Storage::url("images/{$fileName}");
    }


    
    // Find the categories by name and get their IDs
    $categoryNames = json_decode($req->input('categories'));

    $categories = Category::whereIn('name', $categoryNames)->get();
    

    if (count($categoryNames) != count($categories)) {
        return response()->json(['error' => 'One or more category names are invalid']);
    }
    $blog->save();

    // Associate the blog with the categories
    $blog->categories()->sync($categories->pluck('id'));
    
    // try {
    //     $blog->categories()->sync($categories->pluck('id'));
    // } catch (\Exception $e) {
    //     return response()->json(['error' => $e->getMessage()], 'the error is here');
    // }


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
    $blogs = Blog::with('categories')->get();
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
                $blog->load('categories');
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
}
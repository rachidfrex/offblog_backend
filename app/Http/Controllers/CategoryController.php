<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // create a category
    // public function createCategory(Request $request)
    // {
    //     $categoryName = $request->input('name');
    //     $existingCategory = Category::where('name', $categoryName)->first();
    
    //     if ($existingCategory) {
    //         return response()->json(['error' => 'Category name already exists'], 400);
    //     }
    
    //     if ($request->hasFile('image_url')) {
    //         $request->validate([
    //             'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //         ]);
    //         $image = $request->file('image_url');
    //         $name = Str::slug($request->input('this')).'_'.time();
    //         $extension = $image->getClientOriginalExtension();
    //         $fileName = "{$name}.{$extension}";
    //         $image->storeAs('category_images', $fileName, 'public');
    //     } else {
    //         return response()->json(['error' => 'No category image provided'], 400);
    //     }
    
    //     $category = new Category;
    //     $category->name = $categoryName;
    //     $category->image_url = Storage::url("category_images/{$fileName}");
    //     $category->save();
    
    //     return response()->json(['success' => 'Category created successfully'], 200);
    // }
 
public function createCategory(Request $request)
{
    $categoryName = $request->input('name');
    $existingCategory = Category::where('name', $categoryName)->first();

    if ($existingCategory) {
        return response()->json(['error' => 'Category name already exists'], 400);
    }

    if ($request->hasFile('image_url')) {
        $request->validate([
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $image = $request->file('image_url');
        $name = Str::slug($request->input('name')).'_'.time();
        $extension = $image->getClientOriginalExtension();
        $fileName = "{$name}.{$extension}";
        $image->storeAs('public/storage/category_images', $fileName);
    } else {
        return response()->json(['error' => 'No category image provided'], 400);
    }

    $category = new Category;
    $category->name = $categoryName;
    $category->image_url = Storage::url("public/storage/category_images/{$fileName}");
    $category->save();

    return response()->json(['success' => 'Category created successfully'], 200);
}


    // delete a category
    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['success' => 'Category deleted successfully'], 200);
    }
}
<?php

use App\Http\Controllers\userController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//login and register routes
Route::post('/register', [userController::class , 'register']);
Route::post('/login', [userController::class , 'login']);
Route::post('/logout', [userController::class , 'logout']);
Route::get('/user/{id}', [userController::class , 'getUserInfo']);
Route::put('/user/{id}', [userController::class , 'updateProfile']);
Route::put('/user/{id}/password', [userController::class , 'updatePassword']);

// Route::post('/user/{id}', [userController::class , 'updateProfile']);
// get user blogs
Route::get('/user/{userId}/blogs', [BlogController::class , 'getUserBlogs']);


// blog routes
Route::post('/createBlog', [BlogController::class , 'createBlog']);
Route::delete('/blog/{id}', [BlogController::class , 'deleteBlog']);
Route::post('/blogs/{id}/like', 'App\Http\Controllers\BlogController@toggleLike');

//getBlogs
Route::get('/getBlogs', [BlogController::class , 'getBlogs']);
Route::get('/getBlog/{id}', [BlogController::class , 'getBlog']);

//  category
Route::post('/category', [CategoryController::class , 'createCategory']);
Route::delete('/category/{id}', [CategoryController::class , 'deleteCategory']);
Route::get('/categories', [CategoryController::class , 'getCategories']);
// get category blogs
Route::get('/category/{category}/blogs', [BlogController::class, 'getBlogsByCategory']);


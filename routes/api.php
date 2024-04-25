<?php

use App\Http\Controllers\userController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// get user blogs
Route::get('/user/{userId}/blogs', [userController::class , 'getUserBlogs']);


// blog routes
Route::post('/createBlog', [BlogController::class , 'createBlog']);
//getBlogs
Route::get('/getBlogs', [BlogController::class , 'getBlogs']);
Route::get('/getBlog/{id}', [BlogController::class , 'getBlog']);
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{
    // 'name',
    // 'email',
    // 'password',
    // 'username', 
    // 'profile_image',
    // 'role',
   // i want the username to be created automatically from the name and be usique
   
   public function register(Request $req)
   {
       $user = new User;
       $user->name = $req->input('name');
       $user->email = $req->input('email');
       $password = $req->input('password');
       $confirmPassword = $req->input('confirmPassword');
       if ($password !== $confirmPassword) {
           return response()->json(['error' => 'Passwords do not match'], 400);
       }
       
       $existingUser = User::where('email', $user->email)->first();
       if ($existingUser) {
           return response()->json(['error' => 'Email already exists'], 400);
       }
   
    //    Handle the profile image upload
    //    if ($req->hasFile('profile_image')) {
    //        $path = $req->file('profile_image')->storePublicly('profile_images', 'public');
    //        $user->profile_image = $path;
    //    }
    // Handle the profile image upload
    if($req->hasFile('profile_image')) {
        // Validate the uploaded file
        $req->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $req->file('profile_image');

        // Generate a safe file name
        $name = Str::slug($req->input('name')).'_'.time();
        $extension = $image->getClientOriginalExtension();
        $fileName = "{$name}.{$extension}";

        // Store the file in the 'public' disk, in the 'profile_images/' directory
        $image->storeAs('profile_images', $fileName, 'public');

        // Store the full path to the image in the 'profile_image' field
        $user->profile_image = Storage::url("profile_images/{$fileName}");
    }
   
       $user->password = Hash::make($password);
       $user->save();
       if ($user != null) {
           return response()->json(['success' => 'Registration successful'], 201);
       } else {
           return response()->json(['error' => 'Registration failed'], 400);
       }
   }
   
   
   


    // login
    // public function login(Request $req)
    // {
    //     $user = User::where('email', $req->email)->first();
    //     if (!$user || !Hash::check($req->password, $user->password)) {
    //         return response()->json(['error' => 'Invalid email or password'], 401);
    //     }
    //     return response()->json(['success' => 'Login successful'], 200);
    // }
    public function login(Request $req)
{
    $user = User::where('email', $req->email)->first();
    if (!$user || !Hash::check($req->password, $user->password)) {
        return response()->json(['error' => 'Invalid email or password'], 401);
    }
    return response()->json(['success' => 'Login successful', 'user_id' => $user->id], 200);
}

// public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

//     if (Auth::attempt($credentials)) {
//         // Authentication passed...
//         $user = Auth::user();
//         return response()->json([
//             'success' => 'Login successful',
//             'user_id' => $user->id, // Include the user's ID in the response
//         ]);
//     } else {
//         // Authentication failed...
//         return response()->json([
//             'error' => 'Login failed',
//         ]);
//     }
// }
   

    //get user
    public function getUserInfo(Request $req, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['user' => $user], 200);
    }

    // update user
public function updateProfile(Request $req, $id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if ($req->has('name')) {
        $user->name = $req->input('name');
    }
    if ($req->has('email')) {
        $user->email = $req->input('email');
    }

    // Handle the profile image upload
    if($req->hasFile('profile_image')) {
        // Validate the uploaded file
        $req->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $req->file('profile_image');

        // Generate a safe file name
        $name = Str::slug($req->input('name')).'_'.time();
        $extension = $image->getClientOriginalExtension();
        $fileName = "{$name}.{$extension}";

        // Store the file in the 'public' disk, in the 'profile_images/' directory
        $image->storeAs('profile_images', $fileName, 'public');

        // Store the full path to the image in the 'profile_image' field
        $user->profile_image = Storage::url("profile_images/{$fileName}");
    }

    $user->save();

    return response()->json(['success' => 'Profile updated successfully', 'user' => $user], 200);
}




    // logout
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['success' => 'Logout successful'], 200);
    }

}

           
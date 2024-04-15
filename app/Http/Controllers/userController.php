<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        // $user->username = $req->input('username');
        $user->profile_image = $req->input('profile_image');
        $password = $req->input('password');
        $confirmPassword = $req->input('confirmPassword');
        if ($password !== $confirmPassword) {
            return response()->json(['error' => 'Passwords do not match'], 400);
        }
        
        $existingUser = User::where('email', $user->email)->first();
        if ($existingUser) {
            return response()->json(['error' => 'Email already exists'], 400);
        }

        //  
        $user->password = Hash::make($password);
        $user->save();
        if ($user) {

            return response()->json(['success' => 'Registration successful'], 201);
        } else {
            return response()->json(['error' => 'Registration failed'], 400);
        }
        

    }
   


    // login
    public function login(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }
        return response()->json(['success' => 'Login successful'], 200);
    }
    // logout
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['success' => 'Logout successful'], 200);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
   
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

        //  
        $user->password = Hash::make($password);
        $user->save();
        if ($user) {

            return response()->json(['success' => 'Registration successful'], 201);
        } else {
            return response()->json(['error' => 'Registration failed'], 400);
        }
        

    }
}

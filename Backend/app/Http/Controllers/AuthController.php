<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   
use App\Models\RegistrationRequest;
use App\Models\User;

class AuthController extends Controller 
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'pending',
        ]);

        RegistrationRequest::create(['user_id' => $user->id]);

        return response()->json(['message' => 'Registration request submitted.'], 201);
    }
    
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'User logout']);
    }
}

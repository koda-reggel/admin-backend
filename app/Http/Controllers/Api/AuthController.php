<?php

namespace App\Http\Controllers\Api;

use App\Models\User;  // <-- Add this line to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;  // Import Hash facade
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // $fields = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed'
        // ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = $user->createToken($request->name)->accessToken; 
        return  response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Login Attempt', ['email' => $request->email, 'password' => $request->password]);

        // Retrieve the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and if the hashed password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Generate and return a token if credentials are valid
            $token = $user->createToken('YourAppName')->plainTextToken;

            \Log::warning('Failed Login Attempt', ['email' => $request->email]);

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'Logged out successfully'
        ];
    }
}

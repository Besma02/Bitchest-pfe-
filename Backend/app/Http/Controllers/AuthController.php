<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param \App\Http\Services\AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user login and return a token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Call the authentication logic in AuthService
        $user = $this->authService->authenticate($request->email, $request->password);

        // If authentication fails (user is null), return an error message
        if (!$user) {
            return response()->json(['message' => 'Please check your email and password'], 401);
        }

        // Generate a Sanctum token for the authenticated user
        $token = $user->createToken('authToken')->plainTextToken;

        // Return a success message with the token and user details
        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
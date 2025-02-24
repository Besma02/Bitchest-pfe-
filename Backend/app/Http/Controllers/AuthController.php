<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\AuthService;

use App\Mail\PasswordResetMail;
use App\Models\Wallet;

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
    // Valider la demande
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Appeler la logique d'authentification dans AuthService
    $user = $this->authService->authenticate($request->email, $request->password);

    // Si l'authentification échoue (utilisateur est nul), retourner un message d'erreur
    if (!$user) {
        return response()->json(['message' => 'Please check your email and password'], 401);
    }

    // Vérifier si l'utilisateur a un wallet
    $wallet = Wallet::where('idUser', $user->id)->first();
    if (!$wallet) {
        // Si aucun wallet n'est trouvé, créer un nouveau wallet
        $wallet = Wallet::create([
            'idUser' => $user->id,
            'balance' => 0, // Solde initial
        ]);
    }

    // Créer un token Sanctum pour l'utilisateur authentifié
    $token = $user->createToken('authToken')->plainTextToken;

    // Retourner un message de succès avec le token et les informations de l'utilisateur
    return response()->json([
        'message' => 'Login successful.',
        'token' => $token,
        'user' => $user,
        'wallet' => $wallet, // Retourner le wallet si nécessaire
    ]);
}

    public function logout(Request $request)
    {
        
        $user = Auth::user();
        // Supprime le token actuel de l'utilisateur
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'User successfully logged out']);
    }
     // Forgot Password Endpoint
     public function forgotPassword(Request $request)
     {
         $request->validate([
             'email' => 'required|email',
         ]);
 
         $email = $request->email;
         $user = User::where('email', $email)->first();
 
         if (!$user) {
             return response()->json(['message' => 'Email not found'], 404);
         }
 
         // Call the service to handle the password reset logic
         $newPassword = $this->authService->resetPassword($user);
 
         // Send an email with the new password
         Mail::to($user->email)->send(new PasswordResetMail($user, $newPassword));
 
         return response()->json(['message' => 'Password reset successfully. Please check your email for the new password.']);
     }
}

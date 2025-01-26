<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    public function login(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Recherche de l'utilisateur par son email
        $user = User::where('email', $request->email)->first();

        // Vérification du mot de passe
        if ($user && Hash::check($request->password, $user->password)) {
            // Génère un token pour l'utilisateur
            $token = $user->createToken('BitChest')->plainTextToken;

            // Retourne les informations de l'utilisateur et le token
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,  // Vous pouvez ajouter d'autres champs si nécessaire
                ],
                'token' => $token
            ]);
        }

        // Si l'authentification échoue
        return response()->json(['error' => 'Unauthorized'], 401);
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
}

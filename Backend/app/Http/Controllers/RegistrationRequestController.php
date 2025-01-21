<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationRequestController extends Controller
{
    public function store(Request $request)
    {
        // Vérifie si l'email existe déjà dans la table des demandes d'enregistrement
        if (RegistrationRequest::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Your registration request is already submitted. Please wait for approval.',
            ], 409);  // 409 Conflict pour indiquer une demande en attente
        }

        // Vérifie si l'utilisateur est déjà enregistré
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Email already registered. Please log in.',
                'redirect' => '/login',
            ], 409);
        }

        // Valide et crée la demande d'enregistrement
        $validatedData = $request->validate([
            'email' => 'required|email|max:191',
        ]);

        RegistrationRequest::create([
            'email' => $validatedData['email'],
        ]);

        return response()->json([
            'message' => 'Registration request submitted successfully.',
        ]);
    }
}

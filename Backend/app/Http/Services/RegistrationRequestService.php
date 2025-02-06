<?php

namespace App\Http\Services;

use App\Models\RegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserApprovedMail;

class RegistrationRequestService
{

    public function checkExistingRequests(string $email)
    {
        if (RegistrationRequest::where('email', $email)->exists()) {
            return [
                'message' => 'You have already submitted a registration request. Please wait for approval.',
                'status' => 'existing_request'
            ];
        }

        if (User::where('email', $email)->exists()) {
            return [
                'message' => 'Email already registered. Redirecting to Sign In.',
                'redirect' => '/login',
                'status' => 'user_exists'
            ];
        }

        return [
            'message' => 'Your request has been received. You will be notified soon.',
            'status' => 'new_request'
        ];
    }


    public function createRequest(string $email)
    {
        RegistrationRequest::create(['email' => $email]);
    }

    public function getAllRequests()
    {
        return RegistrationRequest::all();
    }

    public function approveRequest($requestId)
    {
        $registrationRequest = RegistrationRequest::findOrFail($requestId);

        // Générer un mot de passe aléatoire
        $password = Str::random(10);

        // Créer l'utilisateur avec l'email fourni
        $user = User::create([
            'email' => $registrationRequest->email,
            'password' => Hash::make($password),
            'role' => 'user', // Définir un rôle par défaut
        ]);

        // Envoyer un e-mail avec le mot de passe
        Mail::to($user->email)->send(new UserApprovedMail($user->email, $password));

        // Marquer la demande comme approuvée
        $registrationRequest->is_approved = true;
        $registrationRequest->save();

        return $user;
    }

    public function rejectRequest($requestId)
    {
        $registrationRequest = RegistrationRequest::findOrFail($requestId);
        $registrationRequest->is_approved = false;
        $registrationRequest->save();

        return $registrationRequest;
    }
}

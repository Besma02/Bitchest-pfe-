<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Récupérer le profil de l'utilisateur connecté
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json($this->profileService->getProfile($user));
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        return response()->json($this->profileService->updateProfile($user, $validatedData));
    }

    /**
     * Modifier le mot de passe utilisateur
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'The new password and confirmation password do not match.'
        ]);

        return response()->json($this->profileService->changePassword($user, $validatedData));
    }
}

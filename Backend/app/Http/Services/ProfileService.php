<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * Récupérer les informations du profil utilisateur.
     */
    public function getProfile(User $user)
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null, // Assurer un chemin correct
        ];
    }

    /**
     * Mettre à jour le profil utilisateur.
     */
    public function updateProfile(User $user, array $data)
    {
        if (isset($data['photo'])) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo); // Supprime l'ancienne photo
            }

            $photoPath = $data['photo']->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        $user->update($data);

        return [
            'message' => 'Profile updated successfully.',
            'user' => $this->getProfile($user), // Retourne les infos mises à jour
        ];
    }

    /**
     * Changer le mot de passe utilisateur.
     */
    public function changePassword(User $user, array $data)
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            return response()->json(['error' => 'Incorrect old password.'], 422);
        }

        $user->update(['password' => Hash::make($data['new_password'])]);

        return [
            'message' => 'Password updated successfully.',
        ];
    }
}

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
        // Vérifier que l'ancien mot de passe est correct
        if (!Hash::check($data['old_password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'The current password is incorrect.',
            ];
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (Hash::check($data['new_password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'The new password must be different from the current password.',
            ];
        }

        // Vérifier la complexité du nouveau mot de passe
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $data['new_password'])) {
            return [
                'success' => false,
                'message' => 'The password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.',
            ];
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return [
            'success' => true,
            'message' => 'Password changed successfully.',
        ];
    }
}

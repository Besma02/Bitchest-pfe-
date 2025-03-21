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
    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
        ];
    }

    /**
     * Mettre à jour le profil utilisateur.
     */
    public function updateProfile(User $user, array $data): array
    {
        // Gestion de la photo de profil
        if (isset($data['photo'])) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $photoPath = $data['photo']->store('photos', 'public');
            $data['photo'] = $photoPath;
        }

        // Vérifier si email_verified_at est null et le mettre à jour si nécessaire
        if ($user->email_verified_at === null) {
            $data['email_verified_at'] = now(); // Date actuelle
        }

        // Mettre à jour l'utilisateur
        $user->update($data);

        return [
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'user' => $this->getProfile($user),
            'email_verified_at' => $user->email_verified_at,
        ];
    }

    /**
     * Changer le mot de passe utilisateur.
     */
    public function changePassword(User $user, array $data): array
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect.',
            ];
        }

        if (Hash::check($data['new_password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Le nouveau mot de passe doit être différent de l\'ancien.',
            ];
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $data['new_password'])) {
            return [
                'success' => false,
                'message' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
            ];
        }

        $user->update(['password' => Hash::make($data['new_password'])]);

        return [
            'success' => true,
            'message' => 'Mot de passe changé avec succès.',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class AdminUserController extends Controller
{
    public function index()
    {
        
        $users = User::all();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        // Valider les données
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de la photo (facultatif)
        ]);

        // Générer un mot de passe aléatoire
        $password = Str::random(10);

        // Créer un utilisateur
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'client', // Rôle par défaut : client
            'password' => Hash::make($password),
        ];

        // Gérer le téléchargement de la photo (si présente)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');  // Stockage dans le dossier 'photos'
            $userData['photo'] = $path;  // Ajouter le chemin de la photo
        }

        // Créer l'utilisateur avec les données
        $user = User::create($userData);

        return response()->json([
            'message' => 'User created successufly',
            'user' => $user,
            'generated_password' => $password,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,  // Retourner l'URL de la photo si elle existe
        ]);
    }

    public function show($id)
    {
        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, $id)
{
    try {
        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Valider les données de mise à jour, y compris la photo
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Préparer les données de mise à jour
        $userData = $request->only(['name', 'email']);

        // Générer un mot de passe aléatoire s'il n'est pas fourni dans la requête
        if (!$request->has('password')) {
            $password = Str::random(10); // Générer un mot de passe aléatoire
            $userData['password'] = Hash::make($password); // Hacher le mot de passe
        }

        // Vérifier si la photo est présente et la gérer
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }

            // Télécharger la nouvelle photo
            $path = $request->file('photo')->store('photos', 'public');
            $userData['photo'] = $path;  // Ajouter le chemin de la photo
        }

        // Mettre à jour l'utilisateur avec les nouvelles données
        $user->update($userData);

        // Retourner la réponse avec un code HTTP 200
        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès.',
            'user' => $user,
            'generated_password' => isset($password) ? $password : null, // Retourner le mot de passe généré si applicable
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
        ], 200);
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Cas où l'utilisateur n'a pas été trouvé
        return response()->json([
            'message' => 'Utilisateur introuvable.',
            'error' => $e->getMessage(),
        ], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Gestion des erreurs de validation
        return response()->json([
            'message' => 'Validation des données échouée.',
            'error' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Cas où une autre erreur survient
        return response()->json([
            'message' => 'Une erreur s\'est produite lors de la mise à jour.',
            'error' => $e->getMessage(),
        ], 500);
    }
}




    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Supprimer l'utilisateur
        $user->delete();

        return response()->json(['message' => 'User deleted successufly']);
    }
}

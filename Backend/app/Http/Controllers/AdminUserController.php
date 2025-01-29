<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;



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
  
    // Envoi d'e-mail avec le mot de passe généré
    try {
        Mail::to($user->email)->send(new SendPasswordMail($user->name, $password));
        Log::debug('Email sent successfully to' . $user->email);
    } catch (\Exception $e) {
        Log::error('Error sending email', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'User created,but an error occurred while sending the email.',
            'error' => $e->getMessage(),
        ], 500);
    }

    // Retourner la réponse avec succès
    return response()->json([
        'message' => 'User created successfully.An email has been sent.',
        'user' => $user,
        'generated_password' => $password,
        'photo' => $user->photo ? asset('storage/' . $user->photo) : null, // Retourner l'URL de la photo si elle existe
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
        // Vérification si les champs nécessaires sont présents
        if (!$request->has('name') || !$request->has('email') || !$request->has('role')) {
            return response()->json([
                'message' => 'Name, email, and role are required.',
            ], 422);
        }

        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,client',  
            'photo' => 'nullable|mimes:jpeg,jpg,png,bmp,gif,svg,webp|max:2048',
        ]);

        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Préparer les données de mise à jour (nom, email, rôle)
        $userData = $request->only(['name', 'email', 'role']); // Inclure le rôle

        // Vérifier si la photo est présente et la gérer
        if ($request->hasFile('photo')) {
            // Si un fichier photo est envoyé
            $photo = $request->file('photo');
            // Vérification de l'extension du fichier
            $path = $photo->storeAs('photos', 'photo_' . time() . '.' . $photo->getClientOriginalExtension(), 'public');
            $userData['photo'] = $path;  // Ajouter le chemin de la photo
        }

        // Mettre à jour l'utilisateur avec les nouvelles données
        $user->update($userData);

        // Retourner la réponse avec un code HTTP 200
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        // Cas où l'utilisateur n'a pas été trouvé
        return response()->json([
            'message' => 'User not found.',
            'error' => $e->getMessage(),
        ], 404);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Gestion des erreurs de validation
        return response()->json([
            'message' => 'Data validation failed.',
            'error' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Cas où une autre erreur survient
        return response()->json([
            'message' => 'An error occurred while updating.',
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

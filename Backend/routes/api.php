<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ces routes sont chargées par le RouteServiceProvider et seront
| assignées au groupe middleware "api" ou "auth:sanctum".
|
*/

// ✅ ROUTE CSRF POUR SANCTUM (obligatoire pour éviter 419)
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});

// ✅ AUTHENTIFICATION PUBLIQUE
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registration-request', [RegistrationRequestController::class, 'store']);

// ✅ DÉCONNEXION PROTÉGÉE PAR SANCTUM
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ✅ ROUTES POUR UTILISATEURS AUTHENTIFIÉS
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);

    // Profil utilisateur
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'getProfile']); // Récupérer le profil
        Route::post('/update', [ProfileController::class, 'updateProfile']); // Modifier le profil
        Route::post('/change-password', [ProfileController::class, 'changePassword']); // Modifier le mot de passe
    });
});

// ✅ ROUTES ADMIN (nécessite auth + rôle admin)
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    // Gestion des utilisateurs
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Gestion des demandes d'inscription
    Route::prefix('registration-requests')->group(function () {
        Route::get('/', [RegistrationRequestController::class, 'index']);
        Route::post('/{id}/approve', [RegistrationRequestController::class, 'approve']);
        Route::post('/{id}/reject', [RegistrationRequestController::class, 'reject']);
    });
});

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
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
});

Route::middleware('api')->group(function () {
    // CSRF Cookie Route for Sanctum Authentication
    Route::get('/sanctum/csrf-cookie', function (Request $request) {
        return response()->noContent();
    });

    // Authentication Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration-request', [RegistrationRequestController::class, 'store']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {

    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

    Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

    Route::get('admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');

    Route::put('admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

    Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');


    Route::get('admin/registration-requests', [RegistrationRequestController::class, 'index']);
    Route::post('admin/registration-requests/{id}/approve', [RegistrationRequestController::class, 'approve']);
    Route::post('admin/registration-requests/{id}/reject', [RegistrationRequestController::class, 'reject']);
});
// Dans routes/api.php
Route::middleware('auth:sanctum')->get('/user-profile', [UserController::class, 'getProfile']);


use App\Http\Controllers\CryptoCotationController;
//récupérer les dernières cotations
Route::get('/crypto-cotations', [CryptoCotationController::class, 'index']);




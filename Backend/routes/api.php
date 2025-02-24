<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\PriceHistoryController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CryptoPurchaseController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    // User Routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // Wallet Routes
    Route::post('/wallet/create', [WalletController::class, 'createWallet']);
    Route::get('/crypto/wallet', [CryptoPurchaseController::class, 'getUserWallet']);

    // Crypto purchase routes
    Route::post('/crypto/buy', [CryptoPurchaseController::class, 'buyCrypto']);
    Route::get('/transactions', [CryptoPurchaseController::class, 'getUserTransactions']);
});

// Public Routes (No authentication required)
Route::middleware('api')->group(function () {
    // Authentication Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration-request', [RegistrationRequestController::class, 'store']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    // Admin User Routes
    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::put('admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Admin Cryptocurrency Routes
    Route::post('admin/cryptocurrencies', [CryptocurrencyController::class, 'store'])->name('admin.cryptocurrency.store');
    Route::put('admin/cryptocurrencies/{id}', [CryptocurrencyController::class, 'update'])->name('admin.cryptocurrency.update');
    Route::get('admin/cryptocurrencies/{id}', [CryptocurrencyController::class, 'show'])->name('cryptocurrency.show');
    // Admin Registration Requests
    Route::get('admin/registration-requests', [RegistrationRequestController::class, 'index']);
    Route::post('admin/registration-requests/{id}/approve', [RegistrationRequestController::class, 'approve']);
    
});

Route::get('cryptocurrencies', [CryptocurrencyController::class, 'getCurrentPrices']);
Route::get('cryptocurrencies/{cryptoName}/history', [CryptocurrencyController::class, 'getPriceHistory']);

// Dans routes/api.php
Route::middleware('auth:sanctum')->get('/user-profile', [UserController::class, 'getProfile']);






//récupérer les dernières cotations
//Route::get('/crypto-cotations', [CryptocurrencyController::class, 'index']);
Route::get('/cryptos/current', [CryptocurrencyController::class, 'getCurrentPrices']);
Route::get('/cryptos/{id}/history', [PriceHistoryController::class, 'getPriceHistory']);

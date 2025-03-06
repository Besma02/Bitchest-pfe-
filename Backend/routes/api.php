<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\StatsController;
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

    //Routes Statistiques
    Route::post('/stats/portfolio', [StatsController::class, 'getUserPortfolio']);
    Route::post('/stats/crypto-details', [StatsController::class, 'getUserCryptoDetails']);
    Route::post('/stats/investments', [StatsController::class, 'getUserInvestments']);
    Route::post('/stats/compare-portfolio', [StatsController::class, 'comparePortfolioValue']);
    Route::post('/stats/crypto-profit-loss', [StatsController::class, 'getCryptoProfitOrLoss']);
    Route::get('/stats/most-popular-cryptos', [StatsController::class, 'getMostPopularCryptos']);
    Route::get('/stats/portfolio-evolution', [StatsController::class, 'getPortfolioEvolution']);
});

Route::middleware('api')->group(function () {

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

    //Stats routes for admin
    Route::get('/stats/total-value', [StatsController::class, 'getPlatformTotalValue']);
    Route::get('/stats/crypto-details', [StatsController::class, 'getPlatformCryptoDetails']);
    Route::get('/stats/top-cryptos', [StatsController::class, 'getTopCryptos']);
    Route::get('/stats/top-cryptos-by-revenue', [StatsController::class, 'getTopCryptosByRevenue']);
    Route::get('/stats/top-buyers', [StatsController::class, 'getTopBuyers']);
    Route::get('/stats/top-wallets', [StatsController::class, 'getTopWallets']);
    Route::get('/stats/total-transaction-volume', [StatsController::class, 'getTotalTransactionVolume']);
    Route::get('/stats/recent-activity', [StatsController::class, 'getRecentActivity']);
    Route::get('/stats/inactive-users', [StatsController::class, 'getInactiveUsers']);
});
// Dans routes/api.php
Route::middleware('auth:sanctum')->get('/user-profile', [UserController::class, 'getProfile']);



use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\PriceHistoryController;

//use App\Http\Controllers\PriceHistoryController;

//récupérer les dernières cotations
Route::get('/cryptos/current', [CryptocurrencyController::class, 'getCurrentPrices']);
Route::get('/cryptos/{id}/history', [PriceHistoryController::class, 'getPriceHistory']);
//Route::get('/cryptos/{id}/history', [PriceHistoryController::class, 'getPriceHistory']);

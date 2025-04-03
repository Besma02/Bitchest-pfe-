<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\PriceHistoryController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptoPurchaseController;
use App\Http\Controllers\CryptoWalletController;


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

// =========================
// 📌 ROUTES PUBLIQUES
// =========================

Route::middleware('api')->group(function () {

    // Authentication Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration-request', [RegistrationRequestController::class, 'store']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);


    //récupérer les dernières cotations
    Route::get('/cryptos/current', [CryptocurrencyController::class, 'getCurrentPrices']);
});


// =========================
// 🔐 ROUTES AUTHENTIFIÉES
// =========================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    //achat de crypto
    Route::post('/crypto/buy', [CryptoPurchaseController::class, 'buyCrypto']);

    //vente de crypto
    Route::post('/crypto/sell', [CryptoPurchaseController::class, 'sellCrypto']);

    Route::get('/cryptos/{id}/history', [PriceHistoryController::class, 'getPriceHistory']);

    //Routes Statistiques
    Route::post('/stats/portfolio', [StatsController::class, 'getUserPortfolio']);
    Route::post('/stats/crypto-details', [StatsController::class, 'getUserCryptoDetails']);
    Route::post('/stats/investments', [StatsController::class, 'getUserInvestments']);
    Route::post('/stats/compare-portfolio', [StatsController::class, 'comparePortfolioValue']);
    Route::post('/stats/crypto-profit-loss', [StatsController::class, 'getCryptoProfitOrLoss']);
    Route::get('/stats/most-popular-cryptos', [StatsController::class, 'getMostPopularCryptos']);
    Route::get('/stats/portfolio-evolution', [StatsController::class, 'getPortfolioEvolution']);


    //Routes Alertes
    Route::get('/alerts', [AlertController::class, 'index']); // Lister toutes les alertes
    Route::post('/alerts', [AlertController::class, 'store']); // Créer une alerte
    Route::put('/alerts/{id}', [AlertController::class, 'update']); // Modifier une alerte
    Route::put('/alerts/{id}/toggle-status', [AlertController::class, 'toggle']); // Activer - desactiver une alerte
    Route::delete('/alerts/{id}', [AlertController::class, 'destroy']); // Supprimer une alerte

    //Routes Notifications
    Route::get('/notifications', [NotificationController::class, 'index']); // Lister toutes les notifications de l'utilisateur
    Route::patch('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

    Route::get('/cryptos/{id}/history', [PriceHistoryController::class, 'getPriceHistory']);
    Route::post('logout', [AuthController::class, 'logout']);
});



// =========================
// 🛡️ ROUTES ADMIN
// =========================

Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {

    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');

    Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');

    Route::get('admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');

    Route::put('admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');

    Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');


    Route::get('admin/registration-requests', [RegistrationRequestController::class, 'index']);
    Route::post('admin/registration-requests/{id}/approve', [RegistrationRequestController::class, 'approve']);
    Route::post('admin/registration-requests/{id}/reject', [RegistrationRequestController::class, 'reject']);

    //Cryptocurrency routes for admin

    //Ajouter une crypto
    Route::post('admin/cryptos', [CryptoPurchaseController::class, 'addCrypto']);

    //Modifier une crypto
    Route::put('/admin/cryptos/{id}', [CryptoPurchaseController::class, 'editCrypto']);


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





//récupérer les dernières cotations
Route::get('/cryptos/current', [CryptocurrencyController::class, 'getCurrentPrices']);





// Route pour créer un wallet (portefeuille)
Route::middleware('auth:sanctum')->post('/wallet/create', [WalletController::class, 'createWallet']);
Route::middleware('auth:sanctum')->get('/wallet', [WalletController::class, 'getWalletInfo']);




Route::middleware('auth:api')->get('/crypto/wallet', [CryptoPurchaseController::class, 'getUserWallet']);
//cryptoWalletDeatils


Route::middleware('auth:sanctum')->get('/crypto/wallet/{id}/purchases', [CryptoWalletController::class, 'getCryptoPurchases']);

//transactions
Route::middleware('auth:sanctum')->get('/transactions', [CryptoPurchaseController::class, 'getUserTransactions']);
//getTotalPurchases
Route::middleware('auth:sanctum')->get('/crypto/total-purchases', [CryptoPurchaseController::class, 'getTotalPurchases']);

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationRequestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which is assigned the "api" middleware group.
|
*/

Route::middleware('api')->group(function () {
    // CSRF Cookie Route for Sanctum Authentication
    Route::get('/sanctum/csrf-cookie', function (Request $request) {
        return response()->noContent();
    });

    // Authentication Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/registration-request', [RegistrationRequestController::class, 'store']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});


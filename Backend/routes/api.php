<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
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
});
Route::post('/registration-request', [RegistrationRequestController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
   
    Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    
    Route::post('admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    
    Route::get('admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    
    Route::put('admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
   
    Route::delete('admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});
// Dans routes/api.php
Route::middleware('auth:sanctum')->get('/user-profile', [UserController::class, 'getProfile']);


<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// use App\Http\Controllers\RegistrationRequestController;

// Route::post('/registration-request', [RegistrationRequestController::class, 'store']);

Route::get('/', function () {
    return view('welcome'); // Or replace 'welcome' with your actual view name
});
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});
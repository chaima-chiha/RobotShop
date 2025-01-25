<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;


// Routes d'authentification
Route::post('/signup', [UserAuthController::class, 'signup']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
//forget password
Route::post('/forgot-password', [UserAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [UserAuthController::class, 'resetPassword']);




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

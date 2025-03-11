<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Api\CartController;


Route::get('/user', function (Request $request) { return $request->user();})->middleware('auth:sanctum');

// Routes d'authentification
Route::post('/signup', [UserAuthController::class, 'signup']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/client/profile', [ClientController::class, 'profile'])->middleware('auth:sanctum');
//Route::get('/client/orders', [ClientController::class, 'orders'])->middleware('auth:sanctum');


//forget password
Route::post('/forgot-password', [UserAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [UserAuthController::class, 'resetPassword']);

//products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
//Route::get('/categories/{category_id}/products', [ProductController::class, 'getByCategory']);


 // Routes pour les catégories
   Route::get('/categories', [CategoryController::class, 'index']);
   Route::get('/categories/{id}', [CategoryController::class, 'show']);
   Route::get('/categories/{id}/products', [CategoryController::class, 'productsByCategory']);

 




Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [ClientController::class, 'getUserDetails']);
    Route::put('/user', [ClientController::class, 'updateUserDetails']);



});






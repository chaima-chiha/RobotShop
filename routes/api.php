<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\OrderController;

//user
//Route::get('/user', function (Request $request) { return $request->user();})->middleware('auth:sanctum');

// Routes d'authentification
Route::post('/signup', [UserAuthController::class, 'signup']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

//forget password
Route::post('/forgot-password', [UserAuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [UserAuthController::class, 'reset']);


Route::get('/search', [SearchController::class, 'search']);

//products
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/recent-products', [ProductController::class, 'indexNew']);
Route::get('/promotion', [ProductController::class, 'indexPromo']);

 // Routes pour les catégories
   Route::get('/categories', [CategoryController::class, 'index']);
   Route::get('/categories/{id}', [CategoryController::class, 'show']);
   Route::get('/categories/{id}/products', [CategoryController::class, 'productsByCategory']);



   Route::middleware('auth:sanctum')->group(function () {
 //Route pour le panier
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::put('/cart/{id}', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'removeAll']);
    Route::get('/cart', [CartController::class, 'index']);

//route pour le profil client
    Route::get('/profile', [ClientController::class, 'profile']);
    Route::get('/orders', [ClientController::class, 'orders']);
    Route::get('/user-details', [ClientController::class, 'getUserDetails']);
    Route::post('/update-user-details', [ClientController::class, 'updateUserDetails']);
//order route
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}/pdf', [OrderController::class, 'downloadPdf']);
});


Route::middleware('auth:sanctum')->get('/orders/{id}/pdf', [OrderController::class, 'downloadPdf']);







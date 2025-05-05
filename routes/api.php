<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;

//user
//Route::get('/user', function (Request $request) { return $request->user();})->middleware('auth:sanctum');

// Routes d'authentification
Route::post('/signup', [UserAuthController::class, 'signup']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');
//forget password
Route::post('/forgot-password', [UserAuthController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [UserAuthController::class, 'reset']);

//recherche de produit et de ctegorie
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

//route avec controle de connexion utilisateur qui verifie le token

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
   Route::put('/update-profile', [ClientController::class, 'updateProfile']);
//order route
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
//modification
Route::put('/orders/{order}', [OrderController::class, 'update']);
});




Route::get('/videos/{id}/with-products', [VideoController::class, 'getWithProducts']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}', [VideoController::class, 'show']);

Route::middleware('auth:sanctum')->post('/videos/{video}/view', [ClientController::class, 'addVideoView']);
Route::middleware('auth:sanctum')->get('/user/video-history', [ClientController::class, 'videoHistory']);

Route::middleware('auth:sanctum')->post('/cart/add-video', [CartController::class, 'addVideoToCart']);
Route::middleware('auth:sanctum')->post('/videos/{video}/verify-code', [VideoController::class, 'verifyActivationCode']);


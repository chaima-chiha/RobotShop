<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;


Route::get('/', function () { return view('client.home'); });

Route::get('/contact', function () { return view('partials.contact'); });

Route::get('/profil', function () {return view('client.profil');});

Route::get('/products', function () {return view('products.index');});

Route::get('/products/{id}', function () {return view('products.show');});

//afficher les produits d'une catégorie
Route::get('/categories/{id}/products', function ($id) { return view('products.categories_show', ['id' => $id]);});

Route::get('/cart',function(){return view('cart.cart');});

//route de reset mdp
Route::get('/reset-password', function () {return view('auth.reset-password');});
Route::get('/order-confirmation', function () { return view('cart.order-confirmation');});


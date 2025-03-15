<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () { return view('client.home'); });

Route::get('/profil', function () {return view('client.profil');});

Route::get('/products', function () {return view('products.index');});

Route::get('/products/{id}', function () {return view('products.show');});

//afficher les produits d'une catégorie
Route::get('/categories/{id}/products', function ($id) { return view('products.categories_show', ['id' => $id]);});



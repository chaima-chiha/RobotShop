<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::get('/', function () { return view('client.home'); });

Route::get('/pofil', function () {return view('client.profil');});

Route::get('/products', function () {return view('products.index');});

Route::get('/products/{id}', function () {return view('products.show');});


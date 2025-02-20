<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Filament\Resources\ProductResource\Api\ProductApiService;

Route::get('/', function () { return view('layouts.app');});
Route::get('/home', function () { return view('client.home'); });

Route::get('/pofil', function () {return view('client.profil');});


Route::get('/products', function () {return view('products.index');});

Route::get('/products/{id}', function () {return view('products.show');});
Route::get('/index', function () { return view('client.index'); });

<?php

use App\Filament\Resources\ProductResource\Api\ProductApiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


//Route::get('/products', function () {
    return view('products');})->middleware('auth:sanctum');

//Route::middleware('auth:sanctum')->post('/{panel}/products', [ProductApiService::class, 'store'])->name('api.admin.products.create');


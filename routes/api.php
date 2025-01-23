<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;

Route::post("login",[UserAuthController::class ,"login"]);
Route::post("signup",[UserAuthController::class ,"signup"]);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

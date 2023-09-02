<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

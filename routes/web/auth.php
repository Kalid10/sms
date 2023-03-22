<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->middleware(['web'])->name('register');

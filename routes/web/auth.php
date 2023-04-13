<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SignUpController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('login/')->middleware('guest')->name('login.')->group(function () {
    Route::post('', [AuthController::class, 'login'])->name('login');
    Route::get('', [AuthController::class, 'index']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');
Route::post('/register', [RegisterController::class, 'register'])->middleware(['auth'])->name('register');
Route::post('/register-bulk', [RegisterController::class, 'bulkRegisterStudents'])->middleware(['auth'])->name('register.bulk');

Route::get('/forgot-password', function () {
    return Inertia::render('Auth/ForgotPassword');
})->name('password.request');

Route::controller(SignUpController::class)->prefix('sign-up/')->middleware('guest')->name('sign-up.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('', 'signUp')->name('post');
});

<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('users/')->middleware('web')->name('users.')->group(function () {
    Route::get('/', 'index');
});

Route::controller(UserController::class)->prefix('user/')->middleware('auth')->name('user.')->group(function () {
    Route::get('profile', 'profile')->name('profile');
    Route::post('update', 'update')->name('update.profile');
    Route::post('update-password', 'updatePassword')->name('update.password');
});

Route::controller(UserController::class)->prefix('register/')->middleware('auth')->name('user.')->group(function () {
    Route::get('family', 'family');
    Route::get('admin', 'admin');
    Route::get('teacher', 'teacher');
});

Route::controller(RegisterController::class)->prefix('register/')->middleware('web')->name('register.')->group(function () {
    Route::get('/', 'index');
    Route::post('admin', 'register')->name('admin');
    Route::post('family', 'register')->name('family');
    Route::post('teacher', 'register')->name('teacher');
});

<?php

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

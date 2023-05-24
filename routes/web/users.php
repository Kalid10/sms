<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('user/')->middleware('auth')->name('user.')->group(function () {
    Route::get('profile', 'profile')->name('profile');
    Route::post('update', 'update')->name('update.profile');
    Route::post('update-password', 'updatePassword')->name('update.password');
});

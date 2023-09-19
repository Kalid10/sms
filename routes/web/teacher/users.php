<?php

use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('teacher/user/')->middleware('checkUserType:teacher')->name('user.')->group(function () {
    Route::get('profile', 'profile')->name('profile');
});

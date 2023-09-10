<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('admin/user/register/')->middleware(['auth'])->name('user.')->group(function () {
    Route::get('student', 'student');
    Route::get('admin', 'admin');
    Route::get('teacher', 'teacher');
    Route::post('upload', 'uploadImage')->name('upload');
});

Route::controller(RegisterController::class)->prefix('register/')->middleware('auth')->name('register.')->group(function () {
    Route::get('/', 'index');
    Route::post('admin', 'register')->name('admin');
    Route::post('guardian', 'register')->name('guardian');
    Route::post('teacher', 'register')->name('teacher');
});

Route::controller(UserController::class)->prefix('admin/user/')->middleware('auth')->name('user.')->group(function () {
    Route::get('profile', 'profile')->name('profile');
    Route::post('update', 'update')->name('update.profile');
    Route::post('update-password', 'updatePassword')->name('update.password');
});

Route::controller(UserController::class)->prefix('admin/user/')->middleware('checkUserType:admin')->name('user.')->group(function () {
    Route::post('block-unblock', 'blockUnblock')->name('block-unblock');
});

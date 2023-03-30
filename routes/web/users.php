<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPositionController;
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
    Route::get('guardian', 'guardian');
    Route::get('admin', 'admin');
    Route::get('teacher', 'teacher');
});

Route::controller(RegisterController::class)->prefix('register/')->middleware('web')->name('register.')->group(function () {
    Route::get('/', 'index');
    Route::post('admin', 'register')->name('admin');
    Route::post('guardian', 'register')->name('guardian');
    Route::post('teacher', 'register')->name('teacher');
});

Route::controller(UserPositionController::class)->prefix('positions/')->middleware(['checkUserType:admin', 'checkUserRole:manage-user-positions'])->name('user-positions.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('{id}', 'destroy')->name('destroy');
});

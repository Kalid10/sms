<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->prefix('register/')->middleware(['auth'])->name('user.')->group(function () {
    Route::get('student', 'student');
    Route::get('admin', 'admin');
    Route::get('teacher', 'teacher');
});

Route::controller(RegisterController::class)->prefix('register/')->middleware('auth')->name('register.')->group(function () {
    Route::get('/', 'index');
    Route::post('admin', 'register')->name('admin');
    Route::post('guardian', 'register')->name('guardian');
    Route::post('teacher', 'register')->name('teacher');
});

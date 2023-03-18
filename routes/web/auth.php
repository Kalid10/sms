<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('login/')->middleware('guest')->name('login.')->group(function () {
    Route::post('', [AuthController::class, 'login']);
    Route::get('', [AuthController::class, 'index']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

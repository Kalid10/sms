<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('login/')->middleware('guest')->name('login.')->group(function () {
    Route::post('', [LoginController::class, 'login']);
    Route::get('', [LoginController::class, 'index']);
});

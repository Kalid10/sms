<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users/')->middleware('web')->name('subjects.')->group(function () {
    Route::get('/', [UserController::class, 'index']);
});

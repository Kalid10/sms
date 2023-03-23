<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users/')->middleware('web')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/create/student', [UserController::class, 'createStudent']);
    Route::get('/create/teacher', [UserController::class, 'createTeacher']);
    Route::get('/create/guardian', [UserController::class, 'createGuardian']);
});

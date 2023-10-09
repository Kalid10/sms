<?php

use App\Http\Controllers\API\Teachers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(TeacherController::class)->prefix('profile/')->middleware('auth:sanctum')->name('profile.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'update')->name('update');
    });
});

<?php

use App\Http\Controllers\API\Guardians\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(TeacherController::class)->prefix('teachers/')->middleware('auth:sanctum')->name('events.')->group(function () {
        Route::get('{teacher?}', 'index')->name('index');
    });
});

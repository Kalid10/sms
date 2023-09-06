<?php

use App\Http\Controllers\API\Teachers\BatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(BatchController::class)->prefix('/batches')->name('batches.')->group(function () {
        Route::get('/{batch}/students', 'students')->name('students');
    });
});

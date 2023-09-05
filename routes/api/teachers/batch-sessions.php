<?php

use App\Http\Controllers\API\Teachers\BatchSessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(BatchSessionController::class)->prefix('/batch-sessions')->name('batchSessions.')->group(function () {
        Route::get('/{batchSession?}', 'index')->name('index');
    });
});

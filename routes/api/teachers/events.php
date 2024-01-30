<?php

use App\Http\Controllers\API\Teachers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->middleware('auth:sanctum')->name('teacher.')->group(function () {
    Route::controller(EventController::class)->prefix('events/')->middleware('auth:sanctum')->name('events.')->group(function () {
        Route::get('', 'index')->name('index');
    });
});

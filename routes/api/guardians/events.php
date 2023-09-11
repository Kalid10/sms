<?php

use App\Http\Controllers\API\Guardians\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(EventController::class)->prefix('events/')->middleware('auth:sanctum')->name('events.')->group(function () {
        Route::get('{student?}', 'index')->name('index');
    });
});

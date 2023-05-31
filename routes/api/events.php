<?php

use App\Http\Controllers\API\EventController;
use Illuminate\Support\Facades\Route;

Route::controller(EventController::class)->prefix('events/')->middleware('auth:sanctum')->name('events.')->group(function () {
    Route::get('{student?}', 'index')->name('index');
});

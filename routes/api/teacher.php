<?php

use App\Http\Controllers\API\TeacherController;
use Illuminate\Support\Facades\Route;

Route::controller(TeacherController::class)->prefix('teachers/')->middleware('auth:sanctum')->name('events.')->group(function () {
    Route::get('{teacher?}', 'index')->name('index');
});

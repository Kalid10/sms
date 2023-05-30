<?php

use App\Http\Controllers\API\StudentController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentController::class)->prefix('children/')->middleware('auth:sanctum')->name('child.')->group(function () {
    Route::get('{student?}', 'index')->name('index');
    Route::get('/notes/{student?}', 'notes')->name('notes');
    Route::get('/sessions/{student?}', 'sessions')->name('sessions');
    Route::get('/subjects/{student?}', 'subjects')->name('subjects');
    Route::get('/schedules/{student?}', 'schedules')->name('schedules');
});

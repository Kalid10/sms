<?php

use App\Http\Controllers\API\GradeController;
use Illuminate\Support\Facades\Route;

Route::controller(GradeController::class)->prefix('grades/')->middleware('auth:sanctum')->name('grades.')->group(function () {
    Route::get('student/{student}', 'index')->name('index');
    Route::get('batch-subject/{batchSubject}/student/{student}', 'batchSubject')->name('batchSubject');
});

<?php

use App\Http\Controllers\API\Guardians\GradeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(GradeController::class)->prefix('grades/')->middleware('auth:sanctum')->name('grades.')->group(function () {
        Route::get('student/{student}', 'index')->name('index');
        Route::get('batch-subject/{batchSubject}/student/{student}', 'batchSubject')->name('batchSubject');
    });
});

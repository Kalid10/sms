<?php

use App\Http\Controllers\API\Teachers\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(AssessmentController::class)->prefix('/assessments')->name('assessment.')->group(function () {
        Route::get('/{assessment?}', 'index')->name('index');
        Route::get('/{assessment}/students', 'students')->name('students');
        Route::post('/{assessment}/mark', 'mark')->name('mark');
    });
});

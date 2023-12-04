<?php

use App\Http\Controllers\API\Teachers\AssessmentTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(AssessmentTypeController::class)->prefix('/assessment-types')->name('assessmentTypes.')->group(function () {
        Route::get('/{assessmentType?}', 'index')->name('index');
    });
});

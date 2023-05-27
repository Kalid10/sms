<?php

use App\Http\Controllers\API\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('assessments/')->middleware('auth:sanctum')->name('assessments.')->group(function () {
    Route::get('', [AssessmentController::class, 'index'])->name('index');
    Route::get('{id}', [AssessmentController::class, 'childAssessment'])->name('child');
});

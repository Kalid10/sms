<?php

use App\Http\Controllers\API\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(AssessmentController::class)->prefix('assessments/')->middleware('auth:sanctum')->name('assessments.')->group(function () {
    Route::get('{student?}', 'index')->name('index');
});

<?php

use App\Http\Controllers\AssessmentTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(AssessmentTypeController::class)->prefix('assessments/type')->middleware(['manage-assessment-types'])->name('assessments.type')->group(function () {
    // Add assessment type routes here
});

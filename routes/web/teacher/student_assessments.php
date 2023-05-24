<?php

use App\Http\Controllers\Web\StudentAssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentAssessmentController::class)->prefix('student-assessments/')->middleware(['checkUserType:teacher'])->name('student-assessments.')->group(function () {
    Route::post('{assessment}/insert', 'insert')->name('insert');
});

<?php

use App\Http\Controllers\API\Guardians\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(StudentController::class)->prefix('children/')->middleware('auth:sanctum')->name('child.')->group(function () {
        Route::get('notes/{student?}', 'notes')->name('notes');
        Route::get('subjects/{student?}', 'subjects')->name('subjects');
        Route::get('schedules/{student?}', 'schedules')->name('schedules');
        Route::get('assessments/{student?}', 'assessments')->name('assessments');
        Route::get('assessment-types/{student?}', 'assessmentTypes')->name('assessmentTypes');
        Route::get('subject-assessments/{batchSubject}/student/{student}', 'subjectAssessments')->name('subjectAssessments');
        Route::get('sessions/{student?}', 'sessions')->name('sessions');
        Route::get('grades/{student?}', 'grades')->name('grades');
        Route::get('terms/{student}', 'terms')->name('terms');
        Route::get('{student}/class/{batchSubject}', 'batchSubject')->name('classes');
        Route::get('{student?}', 'index')->name('index');
        Route::post('{student}', 'update')->name('update');
    });
});

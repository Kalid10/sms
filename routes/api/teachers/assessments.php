<?php

use App\Http\Controllers\API\Teachers\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(AssessmentController::class)->prefix('/assessments')->name('assessment.')->group(function () {
        Route::post('/create-classwork', 'createClassworkAssessment')->name('createClasswork');
        Route::post('/create-homework', 'createHomeworkAssessment')->name('createHomework');
        Route::get('/{assessment?}', 'index')->name('index');
        Route::get('/{assessment}/students', 'students')->name('students');
        Route::post('', 'create')->name('create');
        Route::post('/{assessment}', 'updateAssessment')->name('update');
        Route::post('/{assessment}/update-status', 'updateStatus')->name('updateStatus');
        Route::post('/{assessment}/mark', 'mark')->name('mark');
    });
});

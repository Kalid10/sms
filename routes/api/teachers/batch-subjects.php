<?php

use App\Http\Controllers\API\Teachers\BatchSubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(BatchSubjectController::class)->prefix('/batch-subjects')->name('batchSubjects.')->group(function () {
        Route::get('/{batchSubject?}', 'index')->name('index');
        Route::get('/{batchSubject}/students', 'batchSubjectStudents')->name('students');
    });
});

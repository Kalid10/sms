<?php

use App\Http\Controllers\Web\AssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(AssessmentController::class)->prefix('teacher/assessments/')->middleware(['checkUserType:teacher'])->name('teacher.assessment.')->group(function () {
    Route::get('mark/{assessment}', 'mark')->name('mark');
    Route::post('create', 'create')->name('create');
    Route::get('', 'teacherAssessments')->name('teacher');
    Route::post('update', 'update')->name('update');
    Route::get('{assessment}', 'detail')->name('detail');
    Route::delete('delete/{assessment}', 'delete')->name('delete');
});

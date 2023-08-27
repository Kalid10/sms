<?php

use App\Http\Controllers\Web\Assessments\TeacherAssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(TeacherAssessmentController::class)->prefix('teacher/assessments/')->middleware(['checkUserType:teacher,admin'])->name('teacher.assessment.')->group(function () {
    // TODO: Check if admin has manage-teachers role
    Route::get('mark/{assessment}', 'mark')->name('mark');
    Route::get('', 'teacherAssessments')->name('teacher');
    Route::get('{assessment}', 'detail')->name('detail');

    Route::middleware(['checkUserType:teacher'])->group(function () {
        Route::post('create', 'create')->name('create');
        Route::post('update', 'update')->name('update');
        Route::delete('delete/{assessment}', 'delete')->name('delete');
    });
});

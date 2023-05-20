<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\HomeroomController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherFeedbackController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers/')->middleware(['checkUserRole:manage-teachers', 'auth'])->name('teachers.')->group(function () {
    Route::controller(HomeroomController::class)->group(function () {
        Route::get('homerooms', 'getHomeroomTeachers')->name('homeroom');
        Route::post('assign/homeroom', 'assignHomeroomTeacher')->name('assign.homeroom');
        Route::delete('remove/homeroom/{id}', 'removeHomeroomTeacher')->name('remove.homeroom');
    });

    Route::controller(TeacherController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
    });
});

Route::controller(TeacherFeedbackController::class)->prefix('teacher/feedback/')->middleware(['auth'])->name('teacher.')->group(function () {
    Route::post('add', 'add')->name('feedback.add');
    Route::post('update/{feedback}', 'update')->name('feedback.update');
    Route::delete('delete/{id}', 'destroy')->name('feedback.delete');
});

Route::prefix('teacher/')->middleware(['checkUserType:teacher', 'auth'])->name('teacher.')->group(function () {
    Route::controller(LessonPlanController::class)->prefix('lesson-plan/')->name('lesson-plan.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'updateOrCreate')->name('updateOrCreate');
        Route::delete('delete/{id}', 'destroy')->name('delete');
    });

    // Teacher profile page routes
    Route::controller(TeacherController::class)->group(function () {
        Route::get('', 'show')->name('show');
        Route::get('students/{student}', 'student')->name('student.show');
        Route::get('class/', 'batch')->name('batch.show');
    });

    Route::controller(AssessmentController::class)->prefix('assessments/')->name('assessment.')->group(function () {
        Route::post('create', 'create')->name('create');
        Route::get('', 'teacherAssessments')->name('teacher');
    });
});

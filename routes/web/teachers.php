<?php

use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherFeedbackController;
use Illuminate\Support\Facades\Route;

Route::controller(TeacherController::class)->prefix('teachers/')->middleware(['checkUserRole:manage-teachers'])->name('teachers.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('homerooms', 'getHomeroomTeachers')->name('homeroom');
    Route::get('{id}', 'show')->name('show');
    Route::post('assign/homeroom', 'assignHomeroomTeacher')->name('assign.homeroom');
    Route::delete('remove/homeroom/{id}', 'removeHomeroomTeacher')->name('remove.homeroom');
});

Route::controller(TeacherFeedbackController::class)->prefix('teacher/feedback/')->middleware(['auth'])->name('teacher.')->group(function () {
    Route::post('add', 'add')->name('feedback.add');
    Route::post('update/{feedback}', 'update')->name('feedback.update');
    Route::delete('delete/{id}', 'destroy')->name('feedback.delete');
});

Route::prefix('teacher/')->middleware(['checkUserType:teacher'])->name('teacher.')->group(function () {
    Route::controller(LessonPlanController::class)->prefix('lesson-plan/')->name('lesson-plan.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'updateOrCreate')->name('updateOrCreate');
        Route::delete('delete/{id}', 'destroy')->name('delete');
    });

    // Teacher profile page
    // TODO: If there is a need to change teacher profile page, change this route
    Route::get('', [TeacherController::class, 'show'])->name('show');
});

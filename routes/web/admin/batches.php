<?php

use App\Http\Controllers\Web\BatchController;
use App\Http\Controllers\Web\BatchStudentController;
use App\Http\Controllers\Web\BatchSubjectController;
use Illuminate\Support\Facades\Route;

Route::controller(BatchController::class)->prefix('batches/')->middleware(['checkUserRole:manage-batches'])->name('batches.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('create-bulk', 'createBulk')->name('create-bulk');
    Route::get('', 'list')->name('list');
    Route::get('active', 'active')->name('active');
    Route::post('assign/student', 'assignStudent')->name('assign_student');
    Route::post('assign/subject', 'assignSubject')->name('assign_subject');
});

Route::controller(BatchStudentController::class)->prefix('batches/students/')->middleware(['checkUserRole:manage-students'])->name('batches.student.')->group(function () {
    Route::post('add', 'add')->name('add');
    Route::get('', 'getBatchStudents')->name('get');
    Route::post('transfer', 'transfer')->name('transfer');
});

Route::controller(BatchSubjectController::class)->prefix('batches/subjects/')->middleware(['checkUserRole:manage-subjects'])->name('batches.subjects.')->group(function () {
    Route::get('{batch}/{subject}', 'index')->name('index');
    Route::post('assign', 'assign')->name('assign');
    Route::post('set-sessions', 'setWeeklyFrequency')->name('setWeeklyFrequency');
    Route::post('assign/teacher', 'assignTeacher')->name('assign_teacher');
    Route::post('assign/{batchSubject}/assign-teacher', 'assignTeacherToBatchSubject')->name('assignTeacherToBatchSubject');
});

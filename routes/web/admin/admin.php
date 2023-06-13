<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\LevelController;
use App\Http\Controllers\Web\SchoolScheduleController;
use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\TeacherController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/')->middleware('checkUserType:admin')->name('admin.')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('schedules', 'schedule')->name('schedule');
        Route::get('announcements', 'announcements')->name('announcements');
    });

    Route::controller(TeacherController::class)->prefix('teachers/')->name('teachers.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
    });
    Route::controller(StudentController::class)->prefix('students/')->middleware(['checkUserRole:manage-students'])->name('students.')->group(function () {
        Route::get('{student}', 'show')->name('show');
        Route::get('', 'index')->name('index');
    });
    Route::controller(LevelController::class)->prefix('levels/')->middleware(['checkUserRole:manage-levels'])->name('levels.')->group(function () {
        Route::get('', 'list')->name('list');
        Route::get('/{level}', 'show')->name('show');
        Route::get('/{level}/{batch}, ', 'section')->name('section');
    });
    Route::controller(SubjectController::class)->prefix('subjects/')->middleware(['checkUserRole:manage-subjects'])->name('subjects.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{subject}', 'show')->name('show');
    });

    Route::controller(SchoolScheduleController::class)->prefix('school-schedules/')->middleware(['checkUserRole:manage-school-schedules'])->name('school-schedule.')->group(function () {
        Route::post('create', 'create')->name('create');
    });

    Route::controller(UserController::class)->prefix('users/')->middleware(['auth'])->name('users.')->group(function () {
        Route::get('', 'index');
    });
});

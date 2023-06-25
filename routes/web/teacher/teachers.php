<?php

use App\Http\Controllers\Web\FlagController;
use App\Http\Controllers\Web\Teacher\Announcements;
use App\Http\Controllers\Web\Teacher\Batch;
use App\Http\Controllers\Web\Teacher\Homeroom;
use App\Http\Controllers\Web\Teacher\Student;
use App\Http\Controllers\Web\Teacher\Students;
use App\Http\Controllers\Web\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teacher/')->middleware(['checkUserType:teacher,admin', 'auth'])->name('teacher.')->group(function () {
    Route::controller(TeacherController::class)->group(function () {
        Route::get('', 'show')->name('show');
        Route::get('school-schedule', 'schedule')->name('school-schedule.show');
    });

    Route::get('class', Batch::class)->name('batch.show');
    Route::get('homeroom', Homeroom::class)->name('homeroom.show');
    Route::get('students/{student}', Student::class)->name('student.show');

    Route::post('students/flag', [FlagController::class, 'flagStudent'])->name('student.flag');
    Route::get('students', Students::class)->name('students.show');
    Route::get('announcements', Announcements::class)->name('announcement.show');
});

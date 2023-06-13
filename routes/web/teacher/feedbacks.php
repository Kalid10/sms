<?php

use App\Http\Controllers\Web\AnnouncementController;
use App\Http\Controllers\Web\TeacherFeedbackController;
use Illuminate\Support\Facades\Route;

Route::controller(TeacherFeedbackController::class)->prefix('teacher/feedback/')->middleware(['auth'])->name('teacher.')->group(function () {
    Route::post('add', 'add')->name('feedback.add');
    Route::post('update/{feedback}', 'update')->name('feedback.update');
    Route::delete('delete/{id}', 'destroy')->name('feedback.delete');
});

Route::controller(AnnouncementController::class)->prefix('teacher/announcements/')->middleware(['auth'])->name('announcements.')->group(function () {
    Route::get('', 'index')->name('index');
});

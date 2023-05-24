<?php

use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\StudentNoteController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentController::class)->prefix('students/')->middleware(['checkUserRole:manage-students'])->name('students.')->group(function () {
    Route::get('{student}', 'show')->name('show');
    Route::get('', 'index')->name('index');
});

Route::post('students/{student}/notes/create', [StudentNoteController::class, 'create'])->middleware('auth')->name('create');

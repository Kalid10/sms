<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::controller(NoteController::class)->prefix('notes/')->middleware(['auth'])->name('notes.')->group(function () {
    Route::post('student/add', 'addStudentNote')->name('students.add');
});

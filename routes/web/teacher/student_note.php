<?php

use App\Http\Controllers\Web\StudentNoteController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentNoteController::class)->prefix('teacher/')->middleware(['checkUserType:teacher'])->name('teacher.')->group(function () {
    Route::post('students/{student}/notes/create', 'create')->name('student.note.create ');
    Route::post('students/{student}/notes/update/{studentNote}', 'update')->name('student.note.update');
    Route::delete('students/{student}/notes/delete/{studentNote}', 'delete')->name('student.note.delete');
});

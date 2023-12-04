<?php

use App\Http\Controllers\API\StudentNoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('student.')->middleware('auth:sanctum')->group(function () {
    Route::controller(StudentNoteController::class)->prefix('/student-notes')->name('notes.')->group(function () {
        Route::get('student/{student}', 'index')->name('index');
        Route::get('/{note}', 'note')->name('note');
        Route::post('student/{student}', 'create')->name('create');
        Route::post('student/{student}/{note}', 'updateNote')->name('update');
        Route::delete('{note}', 'delete')->name('delete');
    });
});

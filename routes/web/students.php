<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentController::class)->prefix('students/')->middleware(['checkUserRole:manage-students'])->name('students.')->group(function () {
    Route::get('{student}', 'show')->name('show');
    Route::get('', 'index')->name('index');
});
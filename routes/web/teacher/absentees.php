<?php

use App\Http\Controllers\Web\AbsenteesController;
use Illuminate\Support\Facades\Route;

Route::controller(AbsenteesController::class)->prefix('absentees/')->middleware(['checkUserType:admin'])->name('absentees.')->group(function () {
    Route::post('students/add', 'create')->name('students.add');
    Route::get('student', 'getStudentAbsenteePercentage')->name('student');
});

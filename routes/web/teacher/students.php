<?php

use App\Http\Controllers\Web\StudentController;
use Illuminate\Support\Facades\Route;

Route::controller(StudentController::class)->prefix('teacher/students/')->middleware(['checkUserType:teacher'])->name('teacher.students.')->group(function () {
    Route::post('{student}/conduct/update', 'updateConduct')->name('conduct.update ');
});

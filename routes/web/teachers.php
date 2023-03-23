<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers/')->middleware(['checkUserRole:manage-teachers'])->name('teachers.')->group(function () {
    Route::post('assign/homeroom', [TeacherController::class, 'assignHomeroomTeacher'])->name('assign.homeroom');
    Route::delete('remove/homeroom/{id}', [TeacherController::class, 'removeHomeroomTeacher'])->name('remove.homeroom');
});

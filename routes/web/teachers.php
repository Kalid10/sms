<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::controller(TeacherController::class)->prefix('teachers/')->middleware(['checkUserRole:manage-teachers'])->name('teachers.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('homerooms', 'getHomeroomTeachers')->name('homeroom');
    Route::get('{id}', 'show')->name('show');
    Route::post('assign/homeroom', 'assignHomeroomTeacher')->name('assign.homeroom');
    Route::delete('remove/homeroom/{id}', 'removeHomeroomTeacher')->name('remove.homeroom');
});

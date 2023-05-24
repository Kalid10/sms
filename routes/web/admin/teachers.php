<?php

use App\Http\Controllers\Web\HomeroomController;
use Illuminate\Support\Facades\Route;

Route::prefix('teachers/')->middleware(['checkUserRole:manage-teachers', 'auth'])->name('teachers.')->group(function () {
    Route::controller(HomeroomController::class)->group(function () {
        Route::get('homerooms', 'getHomeroomTeachers')->name('homeroom');
        Route::post('assign/homeroom', 'assignHomeroomTeacher')->name('assign.homeroom');
        Route::delete('remove/homeroom/{id}', 'removeHomeroomTeacher')->name('remove.homeroom');
    });
});

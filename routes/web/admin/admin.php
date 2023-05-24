<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\TeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/')->middleware('checkUserType:admin')->name('admin.')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('schedules', 'schedule')->name('schedule');
    });

    Route::controller(TeacherController::class)->group(function () {
        Route::get('teachers', 'index')->name('index');
        Route::get('teachers/{id}', 'show')->name('show');
    });
});

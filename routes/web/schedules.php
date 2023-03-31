<?php

use App\Http\Controllers\SchoolScheduleController;
use Illuminate\Support\Facades\Route;

Route::controller(SchoolScheduleController::class)->prefix('school-schedules/')->middleware(['checkUserRole:manage-school-schedules'])->name('school-schedule.')->group(function () {
    Route::post('create', 'create')->name('create');
});

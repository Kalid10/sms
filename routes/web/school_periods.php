<?php

use App\Http\Controllers\SchoolPeriodController;
use Illuminate\Support\Facades\Route;

Route::controller(SchoolPeriodController::class)->prefix('school-periods/')->middleware(['checkUserRole:manage-batch-schedules'])->name('school-schedule.')->group(function () {
    Route::post('create', 'create')->name('create');
});

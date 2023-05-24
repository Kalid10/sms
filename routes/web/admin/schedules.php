<?php

use App\Http\Controllers\Web\BatchScheduleController;
use App\Http\Controllers\Web\SchoolScheduleController;
use Illuminate\Support\Facades\Route;

Route::controller(SchoolScheduleController::class)->prefix('school-schedules/')->middleware(['checkUserRole:manage-school-schedules'])->name('school-schedule.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::get('', 'list')->name('list');
    Route::delete('{id}', 'delete')->name('delete');
    Route::post('update', 'update')->name('update');
});

Route::controller(BatchScheduleController::class)->prefix('batch-schedules/')->middleware(['checkUserRole:manage-batch-schedules'])->name('school-schedule.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::get('check', 'checkSchedule')->name('checkSchedule');
});

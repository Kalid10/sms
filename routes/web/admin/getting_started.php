<?php

use App\Http\Controllers\Web\GettingStartedController;
use Illuminate\Support\Facades\Route;

Route::controller(GettingStartedController::class)->prefix('getting-started/')->middleware(['checkUserType:admin', 'checkSchoolYearStatus:0'])->name('getting-started.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::get('school-schedule', 'schoolSchedule')->name('school-schedule');
    Route::get('school-period', 'schoolPeriod')->name('school-period');
    Route::get('class-schedule', 'classSchedule')->name('class-schedule');
    Route::get('finish', 'finish')->name('finish');
    Route::post('batch-schedule/import', 'batchScheduleImport')->name('batch-schedule.import');
});

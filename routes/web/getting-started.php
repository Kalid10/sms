<?php

use App\Http\Controllers\GettingStartedController;
use Illuminate\Support\Facades\Route;

Route::controller(GettingStartedController::class)->prefix('getting-started/')->middleware(['checkUserType:admin'])->name('getting-started.')->group(function () {
    Route::get('', 'index')->name('index')->name('index');
    Route::get('school-schedule', 'schoolSchedule')->name('school-schedule');
});

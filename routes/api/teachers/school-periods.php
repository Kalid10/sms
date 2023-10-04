<?php

use App\Http\Controllers\API\Teachers\SchoolPeriodController;
use Illuminate\Support\Facades\Route;

Route::prefix('/teacher')->name('teacher.')->middleware('auth:sanctum')->group(function () {
    Route::controller(SchoolPeriodController::class)->prefix('/school-periods')->name('school-periods.')->group(function () {
        Route::get('/{schoolPeriod?}', 'index')->name('index');
    });
});

<?php

use App\Http\Controllers\SchoolYearController;
use Illuminate\Support\Facades\Route;

Route::controller(SchoolYearController::class)->prefix('school-year/')->middleware(['checkUserRole:manage-school-years'])->name('school-year.')->group(function () {
    Route::post('create', 'create')->name('create');
});

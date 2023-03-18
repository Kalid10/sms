<?php

use App\Http\Controllers\SchoolYearController;
use Illuminate\Support\Facades\Route;

Route::prefix('school-year/')->middleware(['checkUserRole:manage-school-years'])->name('school-year.')->group(function () {
    Route::post('create', [SchoolYearController::class, 'create'])->name('create');
});

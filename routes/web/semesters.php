<?php

use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

Route::prefix('semester/')->middleware(['checkUserRole:manage-semesters'])->name('semesters.')->group(function () {
    Route::post('create', [SemesterController::class, 'create'])->name('create');
});

<?php

use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

Route::prefix('semesters/')->middleware(['checkUserRole:manage-semesters'])->name('semesters.')->group(function () {
    Route::post('create', [SemesterController::class, 'create'])->name('create');
    Route::post('update', [SemesterController::class, 'update'])->name('update');
    Route::get('', [SemesterController::class, 'list'])->name('list');
    Route::delete('delete/{id}', [SemesterController::class, 'delete'])->name('delete');
});

<?php

use App\Http\Controllers\SemesterController;
use Illuminate\Support\Facades\Route;

Route::controller(SemesterController::class)->prefix('semesters/')->middleware(['checkUserRole:manage-semesters'])->name('semesters.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', [SemesterController::class, 'create'])->name('create');
    Route::post('update', [SemesterController::class, 'update'])->name('update');
    Route::get('list', [SemesterController::class, 'list'])->name('list');
    Route::delete('delete/{id}', [SemesterController::class, 'delete'])->name('delete');
});

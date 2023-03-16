<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('subject/')->middleware(['checkUserRole:manage-subjects'])->name('subjects.')->group(function () {
    Route::post('create', [SubjectController::class, 'create'])->name('create');
    Route::post('update', [SubjectController::class, 'update'])->name('update');
});

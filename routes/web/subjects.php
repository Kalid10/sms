<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('subject/')->middleware(['checkUserRole:manage-subjects'])->name('subject.')->group(function () {
    Route::get('/', [SubjectController::class, 'index'])->name('index');
    Route::post('create', [SubjectController::class, 'create'])->name('create');
    Route::post('update', [SubjectController::class, 'update'])->name('update');
});

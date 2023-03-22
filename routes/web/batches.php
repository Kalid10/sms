<?php

use App\Http\Controllers\BatchController;
use Illuminate\Support\Facades\Route;

Route::prefix('batches/')->middleware(['checkUserRole:manage-batches'])->name('batches.')->group(function () {
    Route::post('create', [BatchController::class, 'create'])->name('create');
});

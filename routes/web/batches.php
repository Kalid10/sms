<?php

use App\Http\Controllers\BatchController;
use Illuminate\Support\Facades\Route;

Route::controller(BatchController::class)->prefix('batches/')->middleware(['checkUserRole:manage-batches'])->name('batches.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('create_bulk', 'createBulk')->name('create');
    Route::get('', 'list')->name('list');
    Route::get('active', 'active')->name('active');
});

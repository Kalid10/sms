<?php

use App\Http\Controllers\Web\FeeController;
use Illuminate\Support\Facades\Route;

Route::controller(FeeController::class)->prefix('admin/fees/')->middleware(['checkUserRole:manage-fees'])->name('fees.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', 'create')->name('create');
});

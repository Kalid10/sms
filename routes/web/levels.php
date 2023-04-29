<?php

use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;

Route::controller(LevelController::class)->prefix('levels/')->middleware(['checkUserRole:manage-levels'])->name('levels.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::get('', 'list')->name('list');
    Route::get('/{level}', 'show')->name('show');
});

<?php

use App\Http\Controllers\Web\LevelCategoryController;
use App\Http\Controllers\Web\LevelController;
use Illuminate\Support\Facades\Route;

Route::controller(LevelController::class)->prefix('levels/')->middleware(['checkUserRole:manage-levels'])->name('levels.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::controller(LevelCategoryController::class)->prefix('level-categories/')->name('level-categories.')->group(function () {
        Route::get('', 'index')->name('index');
    });

});

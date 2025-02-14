<?php

use App\Http\Controllers\Web\LevelCategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(LevelCategoryController::class)->prefix('level-categories/')->middleware(['checkUserRole:manage-levels'])->name('level-categories.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('delete/{id}', 'destroy')->name('delete');
});

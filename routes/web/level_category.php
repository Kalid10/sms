<?php

use App\Http\Controllers\LevelCategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(LevelCategoryController::class)->prefix('level-category/')->middleware(['checkUserRole:manage-levels'])->name('level-category.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('delete/{id}', 'destroy')->name('delete');
});

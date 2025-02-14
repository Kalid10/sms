<?php

use App\Http\Controllers\Web\SemesterController;
use Illuminate\Support\Facades\Route;

Route::controller(SemesterController::class)->prefix('semesters/')->middleware(['checkUserRole:manage-semesters'])->name('semesters.')->group(function () {
    Route::get('{id}', 'show')->name('show');
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::get('', 'list')->name('list');
    Route::delete('delete/{id}', 'delete')->name('delete');
});

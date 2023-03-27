<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::controller(SubjectController::class)->prefix('subject/')->middleware(['checkUserRole:manage-subjects'])->name('subjects.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('delete/{id}', 'delete')->name('delete');
});

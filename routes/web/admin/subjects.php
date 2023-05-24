<?php

use App\Http\Controllers\Web\SubjectController;
use Illuminate\Support\Facades\Route;

Route::controller(SubjectController::class)->prefix('subjects/')->middleware(['checkUserRole:manage-subjects'])->name('subjects.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{subject}', 'show')->name('show');
    Route::post('create', 'create')->name('create');
    Route::post('create-bulk', 'createBulk')->name('create-bulk');
    Route::post('update', 'update')->name('update');
    Route::delete('delete/{id}', 'delete')->name('delete');
    Route::get('restore/{id}', 'restore')->name('restore');
});

<?php

use App\Http\Controllers\Web\QuestionsController;
use Illuminate\Support\Facades\Route;

Route::controller(QuestionsController::class)->prefix('teacher/questions/')->middleware(['checkUserType:teacher'])->name('questions.')->group(function () {
    Route::get('', 'show')->name('show');
    Route::post('create', 'create')->name('create');
    Route::post('', 'update')->name('update');
    Route::post('delete', 'delete')->name('delete');
});

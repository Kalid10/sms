<?php

use App\Http\Controllers\Web\QuestionsController;
use Illuminate\Support\Facades\Route;

Route::controller(QuestionsController::class)->prefix('teacher/questions/')->middleware(['checkUserType:teacher'])->name('questions.')->group(function () {
    Route::get('', 'show')->name('show');
});

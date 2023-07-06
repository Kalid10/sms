<?php

use App\Http\Controllers\Web\LessonPlanController;
use Illuminate\Support\Facades\Route;

Route::controller(LessonPlanController::class)->prefix('teacher/lesson-plan/')->middleware(['checkUserType:teacher'])->name('teacher.lesson-plan.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('generate-question', 'createQuestions')->name('questions');
    Route::get('ai/note', 'noteSuggestions')->name('suggestions');
    Route::post('', 'updateOrCreate')->name('updateOrCreate');
    Route::delete('delete/{id}', 'destroy')->name('delete');
});

<?php

use App\Http\Controllers\Web\CopilotController;
use Illuminate\Support\Facades\Route;

Route::controller(CopilotController::class)->prefix('teacher/copilot')->middleware(['checkUserType:teacher'])->name('copilot.')->group(function () {
    Route::get('', 'show')->name('show');
    Route::get('chat', 'chat')->name('chat');
    Route::post('generate-questions', 'generateQuestions')->name('generate.questions');
});

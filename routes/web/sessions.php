<?php

use App\Http\Controllers\BatchSessionController;
use Illuminate\Support\Facades\Route;

Route::controller(BatchSessionController::class)->prefix('sessions/')->middleware(['auth'])->name('session.')->group(function () {
    Route::get('batch', 'batchSessions')->name('batch');
    Route::get('teacher', 'teacherSessions')->name('teacher');
    Route::get('active/{batch}', 'activeSession')->name('active-session');
});

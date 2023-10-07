<?php

use App\Http\Controllers\API\Guardians\StudentTuitionController;
use Illuminate\Support\Facades\Route;

Route::post('/guardian/tuition/{studentTuition?}/webhook', [StudentTuitionController::class, 'webhook'])->name('webhook');

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(StudentTuitionController::class)->prefix('tuition/')->middleware('auth:sanctum')->name('tuition.')->group(function () {
        Route::get('{studentTuition?}', 'index')->name('index');
        Route::post('{studentTuition?}/checkout', 'processPayment')->name('processPayment');
    });
});

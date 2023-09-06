<?php

use App\Http\Controllers\API\Guardians\BatchSubjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(BatchSubjectController::class)->prefix('batch-subjects/')->middleware('auth:sanctum')->name('batchSubject.')->group(function () {
        Route::get('/{batchSubject}', 'index')->name('index');
    });
});

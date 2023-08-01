<?php

use App\Http\Controllers\API\BatchSubjectController;
use Illuminate\Support\Facades\Route;

Route::controller(BatchSubjectController::class)->prefix('batch-subjects/')->middleware('auth:sanctum')->name('batchSubject.')->group(function () {
    Route::get('/{batchSubject}', 'index')->name('index');
});

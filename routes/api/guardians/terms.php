<?php

use App\Http\Controllers\API\Guardians\TermController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(TermController::class)->prefix('terms/')->middleware('auth:sanctum')->name('term.')->group(function () {
        Route::get('', 'index')->name('index');
    });
});

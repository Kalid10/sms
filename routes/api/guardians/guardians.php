<?php

use App\Http\Controllers\API\Guardians\GuardianController;
use Illuminate\Support\Facades\Route;

Route::prefix('/guardian')->middleware('auth:sanctum')->name('guardian.')->group(function () {
    Route::controller(GuardianController::class)->prefix('profile/')->middleware('auth:sanctum')->name('profile.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'update')->name('update');
    });
});

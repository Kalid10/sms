<?php

use App\Http\Controllers\API\GuardianController;
use Illuminate\Support\Facades\Route;

Route::controller(GuardianController::class)->prefix('profile/')->middleware('auth:sanctum')->name('profile.')->group(function () {
    Route::get('', 'index')->name('index');
});

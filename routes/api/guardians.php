<?php

use App\Http\Controllers\API\GuardianController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile/')->middleware('auth:sanctum')->name('profile.')->group(function () {
    Route::get('', [GuardianController::class, 'index'])->name('index');
    Route::get('/children', [GuardianController::class, 'children'])->name('children');
});

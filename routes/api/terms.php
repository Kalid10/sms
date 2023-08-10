<?php

use App\Http\Controllers\API\TermController;
use Illuminate\Support\Facades\Route;

Route::controller(TermController::class)->prefix('terms/')->middleware('auth:sanctum')->name('term.')->group(function () {
    Route::get('', 'index')->name('index');
});

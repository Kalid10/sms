<?php

use App\Http\Controllers\Web\ExtrasController;
use Illuminate\Support\Facades\Route;

Route::controller(ExtrasController::class)->prefix('/teacher/extras/')->middleware(['checkUserType:teacher'])->name('extras.')->group(function () {
    Route::get('', 'index')->name('index');
});

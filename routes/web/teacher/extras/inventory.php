<?php

use App\Http\Controllers\Web\ExtrasController;
use Illuminate\Support\Facades\Route;

Route::controller(ExtrasController::class)->prefix('/teacher/inventory/')->middleware(['checkUserType:teacher'])->name('inventory.')->group(function () {
    Route::post('update', 'updateInventory')->name('update');
});

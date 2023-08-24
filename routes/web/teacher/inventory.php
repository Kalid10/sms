<?php

use App\Http\Controllers\Web\InventoryController;
use Illuminate\Support\Facades\Route;

Route::controller(InventoryController::class)->prefix('/teacher/inventory/')->middleware(['checkUserType:teacher'])->name('inventory.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('update', 'updateInventory')->name('update');
});
<?php

use App\Http\Controllers\Web\InventoryController;
use Illuminate\Support\Facades\Route;

Route::controller(InventoryController::class)->prefix('/admin/inventory/')->middleware(['checkUserRole:manage-inventory'])->name('inventory.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', 'create')->name('create');
    Route::post('allocate', 'allocateItem')->name('allocate');
    Route::delete('{id}', 'destroy')->name('destroy');
});

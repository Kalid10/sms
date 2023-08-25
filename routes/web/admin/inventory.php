<?php

use App\Http\Controllers\Web\InventoryController;
use Illuminate\Support\Facades\Route;

Route::controller(InventoryController::class)->prefix('/admin/inventory/')->middleware(['checkUserType:admin'])->name('inventory.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', 'create')->name('create')->middleware(['checkUserRole:manage-inventory']);
    Route::post('allocate', 'allocateItem')->name('allocate')->middleware(['checkUserRole:manage-inventory']);
    Route::post('fill', 'fillItem')->name('fill')->middleware(['checkUserRole:manage-inventory']);
});

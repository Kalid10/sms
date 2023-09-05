<?php

use App\Http\Controllers\Web\PenaltyController;
use Illuminate\Support\Facades\Route;

Route::controller(PenaltyController::class)->prefix('admin/penalties/')->middleware(['checkUserRole:manage-finance'])->name('penalties.')->group(function () {
    Route::post('create', 'create')->name('create');
});

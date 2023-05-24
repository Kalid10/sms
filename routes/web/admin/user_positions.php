<?php

use App\Http\Controllers\Web\UserPositionController;
use Illuminate\Support\Facades\Route;

Route::controller(UserPositionController::class)->prefix('positions/')->middleware(['checkUserType:admin', 'checkUserRole:manage-user-positions'])->name('user-positions.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('{id}', 'destroy')->name('destroy');
});

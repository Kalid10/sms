<?php

use App\Http\Controllers\UserPositionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/users', function () {
    return Inertia::render('Users/Index');
});

Route::controller(UserPositionController::class)->prefix('positions/')->middleware(['checkUserType:admin', 'checkUserRole:manage-user-positions'])->name('user-positions.')->group(function () {
    Route::post('create', 'create')->name('create');
});

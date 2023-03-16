<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('roles/')->middleware(['checkUserType:admin', 'checkUserRole:manage-roles'])->name('roles.')->group(function () {
    Route::post('assign', [RoleController::class, 'assign'])->name('assign');
    Route::post('remove', [RoleController::class, 'remove'])->name('remove');
    Route::get('users', [RoleController::class, 'users'])->name('users');
});

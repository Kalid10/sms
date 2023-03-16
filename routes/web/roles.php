<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::post('/assign-roles', [RoleController::class, 'assignRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('assign-role');
Route::post('/remove-roles', [RoleController::class, 'removeRole'])->middleware(['checkUserRole:manage-roles', 'checkUserType:admin'])->name('remove-role');

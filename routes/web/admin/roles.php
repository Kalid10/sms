<?php

use App\Http\Controllers\Web\RoleController;
use Illuminate\Support\Facades\Route;

Route::controller(RoleController::class)->prefix('roles/')->middleware(['checkUserType:admin', 'checkUserRole:manage-roles'])->name('roles.')->group(function () {
    Route::post('assign', 'assign')->name('assign');
    Route::post('remove', 'remove')->name('remove');
    Route::get('users', 'users')->name('users');
    Route::get('activities', 'activities')->name('activities');
    Route::get('', 'list')->name('list');
    Route::get('user/details', 'details')->name('user.details');
});

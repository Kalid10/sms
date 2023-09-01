<?php

use App\Http\Controllers\Web\AbsenteesController;
use Illuminate\Support\Facades\Route;

Route::controller(AbsenteesController::class)->prefix('admin/absentee/staff/')->middleware(['checkUserType:admin'])->name('absentees.staff')->group(function () {
    Route::post('add', [AbsenteesController::class, 'createStaffAbsentee'])->name('add');
    Route::patch('update', [AbsenteesController::class, 'updateStaffAbsentee'])->name('update');
});

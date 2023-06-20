<?php

use App\Http\Controllers\Web\AbsenteesController;
use Illuminate\Support\Facades\Route;

Route::controller(AbsenteesController::class)->prefix('absentee/')->middleware(['checkUserType:admin'])->name('absentees.')->group(function () {
    Route::post('staff/add', [AbsenteesController::class, 'createStaffAbsentee'])->name('staff.add');
});

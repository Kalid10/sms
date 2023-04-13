<?php

use App\Http\Controllers\AbsenteesController;
use Illuminate\Support\Facades\Route;

Route::controller(AbsenteesController::class)->prefix('absentees/')->middleware(['checkUserType:admin'])->name('absentees.')->group(function () {
    Route::post('students/add', 'create')->name('students.add');
});

<?php

use App\Http\Controllers\Web\AssessmentTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(AssessmentTypeController::class)->prefix('assessments/type/')->middleware(['checkUserRole:manage-assessment-types'])->name('assessments.type.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('destroy/{id}', 'destroy')->name('destroy');
});

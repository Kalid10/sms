<?php

use App\Http\Controllers\Web\Assessments\AdminAssessmentController;
use Illuminate\Support\Facades\Route;

Route::controller(AdminAssessmentController::class)->prefix('admin/assessments/')->middleware(['checkUserType:admin'])->name('assessments.')->group(function () {
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('delete/{assessment}', 'delete')->name('delete');
});

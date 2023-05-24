<?php

use App\Http\Controllers\Web\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::controller(AnnouncementController::class)->prefix('announcements/')->middleware(['checkUserRole:manage-announcements'])->name('announcements.')->group(function () {
    Route::get('', 'index')->name('index');
    Route::post('create', 'create')->name('create');
    Route::post('update', 'update')->name('update');
    Route::delete('{id}', 'destroy')->name('destroy');
});

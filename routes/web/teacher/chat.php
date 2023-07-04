<?php

use App\Http\Controllers\API\MessagesController;
use Illuminate\Support\Facades\Route;

Route::controller(MessagesController::class)->prefix('teacher/chat/')->middleware(['checkUserType:teacher'])->name('chat.')->group(function () {
    Route::get('', 'index')->name('index');
});

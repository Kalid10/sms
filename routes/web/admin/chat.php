<?php

use App\Http\Controllers\API\MessagesController;
use Illuminate\Support\Facades\Route;

Route::controller(MessagesController::class)->prefix('admin/chat/')->middleware(['checkUserType:admin'])->name('chat.')->group(function () {
    Route::get('', 'index')->name('index');
});

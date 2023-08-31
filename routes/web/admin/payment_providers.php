<?php

use App\Http\Controllers\Web\PaymentProviderController;
use Illuminate\Support\Facades\Route;

Route::controller(PaymentProviderController::class)->prefix('admin/payment-providers/')->middleware(['checkUserRole:manage-fees'])->name('payment.providers.')->group(function () {
    Route::post('create', 'create')->name('create');

    Route::post('upload', 'uploadImage')->name('upload');
});

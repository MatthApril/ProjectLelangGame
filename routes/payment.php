<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('payment')->as('payment.')
->middleware(['auth', 'check_role:user,seller', 'check_status'])
->group(function() {
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
});

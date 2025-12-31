<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// MIDTRANS PAYMENT
Route::prefix('payment')->as('payment.')
->middleware(['auth', 'check_role:user,seller', 'check_status', 'check_banned'])
->group(function() {
    Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout.form');
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/payment', [PaymentController::class, 'showPayment'])->middleware('check_expire_payment')->name('show.payment');
    Route::get('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
});

// XENDIT PAYMENT
// Route::prefix('xendit-payment')->as('xendit-payment.')
// ->middleware(['auth', 'check_role:user,seller', 'check_status'])
// ->group(function() {
//     Route::get('/xendit-checkout', [PaymentController::class, 'showXenditCheckout'])->name('checkout.form');
//     Route::post('/xendit-checkout', [PaymentController::class, 'createInvoice'])->name('checkout');
//     // Route::get('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
//     // Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
// });

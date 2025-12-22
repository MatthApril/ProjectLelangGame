<?php

use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')
    ->middleware(['auth', 'check_role:seller', 'check_status'])
    ->group(function() {
        Route::get('/', [SellerController::class, 'showDashboard'])->name('dashboard');
});


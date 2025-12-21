<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')
    ->middleware(['auth', 'check_role:seller'])
    ->group(function() {
        Route::get('/', [AdminController::class, 'showDashboard'])->name('dashboard');
});

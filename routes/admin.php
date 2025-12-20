<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')->group(function() {
    Route::get('/', [AdminController::class, 'showDashboard'])->name('admin.dashboard');
});

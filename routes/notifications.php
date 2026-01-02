<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->as('notifications.')
    ->middleware(['auth', 'check_role:user,seller', 'check_status'])
    ->group(function() {
        Route::get('/', [NotificationController::class, 'show'])->name('index');
        Route::get('/{id}', [NotificationController::class, 'showDetail'])->name('detail');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

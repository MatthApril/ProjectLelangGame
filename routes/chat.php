<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::controller(ChatController::class)
    ->middleware(['auth', 'check_status', 'check_banned'])
    ->prefix('chat')
    ->as('chat.')
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/{userId}', 'open')->name('open');
        Route::post('/{userId}', 'store')->name('store');
    });
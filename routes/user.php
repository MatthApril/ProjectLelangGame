<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [UserController::class, 'showHome'])->name('user.home');

Route::prefix('user')->as('user.')
    ->middleware(['auth', "check_role:user", 'check_status'])
    ->group(function() {
        Route::controller(UserController::class)->group(function() {

        });

        Route::controller(ChatController::class)->group(function() {
            Route::get('/chat/{userId}', 'show')->name('chat.show');
            Route::post('/chat/{userId}', 'store')->name('chat.store');
        });
});


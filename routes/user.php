<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/home', [UserController::class, 'showHome'])->name('user.home');

Route::prefix('user')->as('user.')
    ->middleware(['auth', 'check_status'])
    ->group(function() {
});


<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->as('user.')->group(function() {
    Route::get('/', [UserController::class, 'showHome'])->name('home');
});


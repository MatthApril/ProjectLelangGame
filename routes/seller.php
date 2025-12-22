<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')->group(function() {
    Route::get('/', [UserController::class, 'showHome'])->name('home');
});


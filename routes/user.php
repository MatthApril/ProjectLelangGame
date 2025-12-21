<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/user', [UserController::class, 'showHome'])->name('user.home');

Route::prefix('user')->as('user.')
    ->middleware(['auth', 'check_role:user', 'check_status'])
    ->group(function() {

});


<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')
    ->middleware(['auth', 'check_role:seller', 'check_status', 'check_banned', 'throttle:api'])
    ->group(function() {
        Route::controller(SellerController::class)->group(function() {
            Route::get('/', 'showDashboard')->name('dashboard');
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::post('/products', 'store')->name('products.store');
            Route::get('/products/{id}/edit', 'edit')->name('products.edit');
            Route::put('/products/{id}', 'update')->name('products.update');
            Route::delete('/products/{id}', 'destroy')->name('products.destroy');

            Route::get('/games/{game}/categories', 'getCategoriesByGame')->name('games.categories');
            Route::get('/reviews', 'showReviews')->name('reviews.index');
        });

        Route::controller(ChatController::class)->group(function() {
            Route::get('/chat/{userId}', 'show')->name('chat.show');
            Route::post('/chat/{userId}', 'store')->name('chat.store');
        });
    });


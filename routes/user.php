<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//gk perlu login
Route::get('/home', [UserController::class, 'showHome'])->name('user.home');
Route::get('/games', [UserController::class, 'showGames'])->name('games.index');
Route::get('/games/{id}', [UserController::class, 'showGameDetail'])->name('games.detail');
Route::get('/products', [UserController::class, 'showProducts'])->name('products.index');
Route::get('/products/{id}', [UserController::class, 'showProductDetail'])->name('products.detail');
Route::get('/shops/{id}', [UserController::class, 'showShop'])->name('shops.detail');

Route::prefix('user')->as('user.')
    ->middleware(['auth', "check_role:user", 'check_status'])
    ->group(function() {
        Route::controller(UserController::class)->group(function() {
            Route::get('/cart', 'showCart')->name('cart');
            Route::post('/cart/add/{productId}', 'addToCart')->name('cart.add');
            Route::delete('/cart/remove/{cartItemId}', 'removeFromCart')->name('cart.remove');
            Route::put('/cart/update/{cartItemId}', 'updateCartQuantity')->name('cart.update');
        });

        Route::controller(ChatController::class)->group(function() {
            Route::get('/chat/{userId}', 'show')->name('chat.show');
            Route::post('/chat/{userId}', 'store')->name('chat.store');
        });
    });

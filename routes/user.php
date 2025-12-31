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
->middleware(['auth', 'check_role:user,seller', 'check_status'])
->group(function() {
    Route::get('/cart', [UserController::class, 'showCart'])->name('cart');
    Route::get('/orders', [UserController::class, 'showOrders'])->name('orders');
    Route::get('/cart/partial', [UserController::class, 'showCartPartial'])->name('cart.partial');
    Route::post('/cart/update', [UserController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/add/{productId}', [UserController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{cartItemId}', [UserController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/orders/{orderId}', [UserController::class, 'showOrderDetail'])->name('orders.detail');
    Route::post('/reviews/{orderItemId}', [UserController::class, 'storeReview'])->name('reviews.store');
    
    Route::controller(ChatController::class)->group(function() {
        Route::get('/chat/{userId}', 'show')->name('chat.show');
        Route::post('/chat/{userId}', 'store')->name('chat.store');
    });
});


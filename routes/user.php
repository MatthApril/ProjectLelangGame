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
Route::get('/auctions', [UserController::class, 'showAuctions'])->name('auctions.index');
Route::get('/auctions/{auctionId}', [UserController::class, 'showAuctionDetail'])->name('auctions.detail');

Route::prefix('user')->as('user.')
->middleware(['auth', 'check_role:user,seller', 'check_status', 'throttle:api', 'check_banned'])
->group(function() {
    Route::controller(UserController::class)->group(function() {
        Route::get('/cart','showCart')->name('cart');
        Route::get('/orders', 'showOrders')->name('orders');
        Route::get('/cart/partial', 'showCartPartial')->name('cart.partial');
        Route::post('/cart/update', 'updateCart')->name('cart.update');
        Route::post('/cart/add/{productId}', 'addToCart')->name('cart.add');
        Route::post('/cart/remove/{cartItemId}', 'removeFromCart')->name('cart.remove');
        Route::get('/orders/{orderId}', 'showOrderDetail')->name('orders.detail');
        Route::get('/topup', 'topUp')->name('topUp');
        Route::get('/joki', 'joki')->name('joki');
        Route::get('/akun', 'akun')->name('akun');
        Route::get('/item', 'item')->name('item');

        Route::post('/auctions/bid/{auctionId}', 'placeBid')->name('auctions.bid');
        Route::post('/reviews/{orderItemId}', 'storeReview')->name('reviews.store');
    });
});


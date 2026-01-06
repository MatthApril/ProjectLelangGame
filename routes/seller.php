<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')
    ->middleware(['auth', 'check_role:seller', 'check_status', 'check_banned', 'throttle:api'])
    ->group(function() {
        Route::controller(SellerController::class)->group(function() {
            Route::get('/', 'showDashboard')->name('dashboard');
            Route::post('/shop/toggle-status', 'toggleShopStatus')->name('shop.toggle-status');
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::post('/products', 'store')->name('products.store');
            Route::get('/products/{id}/edit', 'edit')->name('products.edit');
            Route::put('/products/{id}', 'update')->name('products.update');
            Route::delete('/products/{id}', 'destroy')->name('products.destroy');
            Route::post('/products/{id}/restore', 'restore')->name('products.restore');

            Route::get('/games/{game}/categories', 'getCategoriesByGame')->name('games.categories');
            Route::get('/reviews', 'showReviews')->name('reviews.index');

            Route::get('/incoming_orders', 'showIncomingOrders')->name('incoming_orders.index');
            Route::post('/orders/{orderItemId}/ship','shipOrder')->name('orders.ship');
            Route::post('/orders/{orderItemId}/cancel','cancelOrder')->name('orders.cancel');

            Route::get('/auctions', 'showSellerAuctions')->name('auctions.index');
            Route::get('/auctions/create', 'showCreateAuctionForm')->name('auctions.create.form');
            Route::post('/auctions/create', 'createAuction')->name('auctions.create');
            Route::get('/auctions/{auctionId}', 'showSellerAuctionDetail')->name('auctions.detail');


            Route::get('/complaints', 'showComplaints')->name('complaints.index');
            Route::get('/complaints/{complaintId}', 'showComplaintDetail')->name('complaints.show');
            Route::post('/complaints/{complaintId}/respond', 'respondComplaint')->name('complaints.respond');
        });
});

<?php

use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')->middleware(['auth', 'check_role:seller', 'check_status'])->group(function() {
        Route::get('/', [SellerController::class, 'showDashboard'])->name('dashboard');

        Route::get('/products', [SellerController::class, 'index'])->name('products.index');
        Route::get('/products/create', [SellerController::class, 'create'])->name('products.create');
        Route::post('/products', [SellerController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [SellerController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [SellerController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [SellerController::class, 'destroy'])->name('products.destroy');

        Route::get('/games/{game}/categories', [SellerController::class, 'getCategoriesByGame'])->name('games.categories');
});


<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')->middleware(['auth', 'check_role:admin'])->group(function() {
        Route::get('/', [AdminController::class, 'showDashboard'])->name('dashboard');

        Route::get('/categories', [AdminController::class, 'showCategories'])->name('categories.index');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{category}/edit', [AdminController::class, 'showEditCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.destroy');

        Route::get('/games', [AdminController::class, 'showGames'])->name('games.index');
        Route::get('/games/create', [AdminController::class, 'showCreateGame'])->name('games.create');
        Route::post('/games', [AdminController::class, 'storeGame'])->name('games.store');
        Route::get('/games/{game}/edit', [AdminController::class, 'showEditGame'])->name('games.edit');
        Route::put('/games/{game}', [AdminController::class, 'updateGame'])->name('games.update');
        Route::delete('/games/{game}', [AdminController::class, 'deleteGame'])->name('games.destroy');

        Route::get('/users', [AdminController::class, 'showUsers'])->name('users.index');
        Route::post('/users/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
        Route::post('/users/ban', [AdminController::class, 'banUser'])->name('users.ban');

        Route::get('/comments', [AdminController::class, 'showComments'])->name('comments.index');
        Route::delete('/comments/{comment}', [AdminController::class, 'deleteComment'])->name('comments.destroy');
});

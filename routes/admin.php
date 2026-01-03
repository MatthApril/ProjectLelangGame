<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')
    ->middleware(['auth', 'check_role:admin'])
    ->group(function() {
        Route::controller(AdminController::class)->group(function() {
            Route::get('/', 'showDashboard')->name('dashboard');

            Route::get('/categories', 'showCategories')->name('categories.index');
            Route::post('/categories', 'storeCategory')->name('categories.store');
            Route::get('/categories/{category}/edit', 'showEditCategory')->name('categories.edit');
            Route::put('/categories/{category}', 'updateCategory')->name('categories.update');
            Route::delete('/categories/{category}', 'deleteCategory')->name('categories.destroy');
        Route::post('/categories/restore', [AdminController::class, 'restoreCategory'])->name('categories.restore');

            Route::get('/games', 'showGames')->name('games.index');
            Route::post('/games', 'storeGame')->name('games.store');
            Route::get('/games/{game}/edit', 'showEditGame')->name('games.edit');
            Route::put('/games/{game}', 'updateGame')->name('games.update');
            Route::delete('/games/{game}', 'deleteGame')->name('games.destroy');
        Route::post('/games/restore', [AdminController::class, 'restoreGame'])->name('games.restore');

            Route::get('/users', 'showUsers')->name('users.index');
            Route::post('/users/unban', 'unbanUser')->name('users.unban');
            Route::post('/users/ban', 'banUser')->name('users.ban');
            Route::get('/comments', 'showComments')->name('comments.index');
            Route::delete('/comments/{comment}', 'deleteComment')->name('comments.destroy');

            Route::get('/templates', 'showNotificationMaster')->name('notifications.index');
            Route::post('/templates', 'storeNotificationTemplate')->name('notifications.store');
            Route::get('/templates/edit/{template}', 'showEditNotificationTemplate')->name('notifications.edit');
            Route::put('/templates/{template}', 'updateNotificationTemplate')->name('notifications.update');
            Route::delete('/templates/{template}', 'deleteNotificationTemplate')->name('notifications.destroy');
            Route::post('/templates/broadcast/{template}', 'broadcastNotification')->name('notifications.broadcast');
        });

        Route::controller(ChatController::class)->group(function() {
            Route::get('/chat/{userId}', 'show')->name('chat.show');
            Route::post('/chat/{userId}', 'store')->name('chat.store');
        });
});

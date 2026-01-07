<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Message;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useBootstrapFive();
        // Kalau di host jangan lupa uncomment ini
        // URL::forceScheme('https');

        // "Composers" allow us to share variables to specific views
        // '*' means "Share this with ALL views" (or specify 'layouts.app')
        View::composer('*', function ($view) {
            $unreadMessagesCount = 0;
            $unreadNotificationsCount = 0;
            $cartItemCount = 0;

            if (Auth::check()) {
                $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->distinct('sender_id') // This ensures 50 messages from User A only counts as "1"
                    ->count('sender_id');

                $unreadNotificationsCount = NotificationRecipient::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $cartItemCount = $cart->cartItems()->count();
                }
            }

            // Now the variable $unreadCount is available in every blade file
            $view->with('unreadMessagesCount', $unreadMessagesCount);
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
            $view->with('cartItemCount', $cartItemCount);
        });
    }
}

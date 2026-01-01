<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckExpirePayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orderId = $request->input('order_id');
        $order = Order::where('order_id', $orderId)->first();
        if (now()->greaterThan($order->expire_payment_at)) {
            return redirect()->route('user.orders')->with('error', 'Payment period has expired.');
        }
        return $next($request);
    }
}

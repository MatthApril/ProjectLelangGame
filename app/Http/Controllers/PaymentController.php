<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function checkout(Request $req)
    {
        $user = Auth::user();

        $cart = $user->cart;

        $cart_items = $cart
                ? $cart->cartItems()
                    ->whereHas('product', function ($q) {
                        $q->whereNull('deleted_at');
                    })
                    ->with('product')
                    ->get()
                : collect();

        if (!$cart || $cart_items->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;

            $orderId = 'ORD-' . Str::uuid();

            $order = Order::create([
                'order_id' => $orderId,
                'user_id' => $user->user_id,
                'shop_id' => $cart->cartItems->first()->product->shop_id,
                'status' => 'pending',
                'total_prices' => 0,
            ]);

            foreach ($cart_items as $item) {

                $product = Product::lockForUpdate()->findOrFail($item->product_id);

                if ($product->stok < $item->quantity) {
                    throw new \Exception("Stok {$product->name} tidak cukup");
                }
                // dd($product->stok);

                $subtotal = $product->price * $item->quantity;
                $totalPrice += $subtotal;

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'shop_id' => $product->shop_id,
                    'product_price' => $product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                    'status' => 'pending',
                ]);

            }

            $order->update(['total_prices' => $totalPrice]);
            // $product->decrement('stok', $item->quantity);
            // $cart->cartItems()->delete();

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('user.cart')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }

        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->total_prices,
            ],
            'customer_details' => [
                'name' => $user->username,
                'email' => $user->email,
            ],
        ];

        \Illuminate\Support\Facades\Log::info('MIDTRANS PARAMS', $params);
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('pages.payments.checkout', compact('snapToken', 'order'));
    }

    public function callback (Request $req)
    {
        $server_key = config('midtrans.server_key');
        $hashed = hash("sha512", $req->order_id . $req->status_code . $req->gross_amount . $server_key);
        if ($hashed == $req->signature_key) {
            $order = Order::where('order_id', $req->order_id)->first();
            if ($order) {

            }
        }
    }

}

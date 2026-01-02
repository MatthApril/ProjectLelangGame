<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class PaymentController extends Controller
{
    // // XENDIT PAYMENT
    // public function __construct()
    // {
    //     Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    // }

    // public function showXenditCheckout() {
    //     return view('pages.payments.checkout_xendit');
    // }

    // public function createInvoice() {
    //     $user = Auth::user();

    //     $cart = $user->cart;

    //     $cart_items = $cart
    //             ? $cart->cartItems()
    //                 ->whereHas('product', function ($q) {
    //                     $q->whereNull('deleted_at');
    //                 })
    //                 ->with('product')
    //                 ->get()
    //             : collect();

    //     if (!$cart || $cart_items->isEmpty()) {
    //         return redirect()->route('user.cart')->with('error', 'Keranjang kosong.');
    //     }


    //     DB::beginTransaction();

    //     try {
    //         $totalPrice = 0;
    //         $orderId = 'ORD-' . Str::uuid();

    //         $order = Order::create([
    //             'order_id' => $orderId,
    //             'user_id' => $user->user_id,
    //             'shop_id' => $cart->cartItems->first()->product->shop_id,
    //             'status' => 'unpaid',
    //             'total_prices' => 0,
    //         ]);

    //         foreach ($cart_items as $item) {

    //             $product = Product::lockForUpdate()->findOrFail($item->product_id);

    //             if ($product->stok < $item->quantity) {
    //                 throw new \Exception("Stok {$product->name} tidak cukup");
    //             }
    //             // dd($product->stok);

    //             $subtotal = $product->price * $item->quantity;
    //             $totalPrice += $subtotal;

    //             OrderItem::create([
    //                 'order_id' => $order->order_id,
    //                 'product_id' => $product->product_id,
    //                 'shop_id' => $product->shop_id,
    //                 'product_price' => $product->price,
    //                 'quantity' => $item->quantity,
    //                 'subtotal' => $subtotal,
    //                 'status' => 'pending',
    //             ]);

    //         }

    //         $order->update(['total_prices' => $totalPrice]);
    //         // $product->decrement('stok', $item->quantity);
    //         // $cart->cartItems()->delete();


    //         $apiInstance = new InvoiceApi();
    //         $createInvoice = new CreateInvoiceRequest([
    //             'external_id' => $orderId,
    //             'amount' => $totalPrice,
    //             'payer_email' => $user->email
    //         ]);

    //         try {
    //             $generateInvoice = $apiInstance->createInvoice($createInvoice);
    //             dd($generateInvoice);
    //             $order->update(['payment_url' => $generateInvoice->invoice_url]);
    //         } catch (\Xendit\XenditSdkException $e) {
    //             dd($e->getFullError());
    //         }

    //         DB::commit();

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return redirect()->route('user.cart')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
    //     }

    // }


    // MIDTRANS PAYMENT
    public function showCheckout()
    {
        return view('pages.payments.checkout');
    }

    public function showPayment(Request $req)
    {
        $req->validate([
            'order_id' => 'required|string',
        ]);

        $order = Order::where('order_id', $req->order_id)->first();
        $snapToken = $order->snap_token;
        return view('pages.payments.checkout', compact('snapToken', 'order'));
    }

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

        foreach ($cart_items as $item) {

            $owner = $item->product->shop->owner;
            if (!$owner) {
                CartItem::where('product_id', $item->product_id)->delete();
                return redirect()->route('user.cart')->with('error', 'Anda tidak dapat membeli produk dari penjual yang dibanned. Produk telah dihapus dari keranjang.');
            }

            $game = $item->product->game;
            if (!$game) {
                CartItem::where('product_id', $item->product_id)->delete();
                return redirect()->route('user.cart')->with('error', 'Produk tidak tersedia karena game terkait telah dihapus. Produk telah dihapus dari keranjang.');
            }

        }

        DB::beginTransaction();

        try {
            $totalPrice = 0;

            $orderId = 'ORD-' . Str::uuid();

            $order = Order::create([
                'order_id' => $orderId,
                'user_id' => $user->user_id,
                'shop_id' => $cart->cartItems->first()->product->shop_id,
                'status' => 'unpaid',
                'expire_payment_at' => now()->addHours(1),
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
            'callbacks' => [
                'finish' => route('payment.midtrans.callback'),
            ],
        ];

        \Illuminate\Support\Facades\Log::info('MIDTRANS PARAMS', $params);
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        $order->update(['snap_token' => $snapToken]);

        return view('pages.payments.checkout', compact('snapToken', 'order'));
    }

    public function callback (Request $req)
    {
        // dd($req->all());
        if ($req->transaction_status == 'capture') {
            $order = Order::where('order_id', $req->order_id)->first();
            if ($order) {
                $order->update(['status' => 'paid']);
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stok', $item->quantity);
                    }
                    $item->update(['status' => 'shipped']);
                }

                $user = Auth::user();
                // dd($user->cart);
                $cart = $user->cart->first();
                $cart->cartItems()->delete();

                return redirect()->route('user.orders')->with('success', 'Pembayaran berhasil!');
            }
        }

        if ($req->transaction_status == 'expire') {
            $order = Order::where('order_id', $req->order_id)->first();
            if ($order) {
                $order->update(['status' => 'expire']);
            }
        }

        return redirect()->route('user.orders')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

}

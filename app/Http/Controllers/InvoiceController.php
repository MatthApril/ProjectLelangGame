<?php

namespace App\Http\Controllers;

use App\Mail\Invoice;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    function invoice($order_id){
        $order = Order::with(['account', 'orderItems.product'])->where('order_id', $order_id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan.');
        }

        if($order->status != 'paid'){
            return redirect()->back()->with('error', 'Order belum dibayar.');
        }

        Mail::to($order->account->email)->queue(new Invoice($order));
        return redirect()->back()->with('success', 'Invoice berhasil dikirim ke email Anda.');
    }
}

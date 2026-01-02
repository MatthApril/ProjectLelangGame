<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Category;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Request $request, $userId) {
        $myId = Auth::user()->user_id;
        $otherUser = User::findOrFail($userId);
        $otherUserId = $userId;
        $categories = Category::orderBy('category_name')->get();

        $product = null;
        $autoMessage = '';

        $messages = Message::where(function($query) use ($myId, $userId) {
            $query->where('sender_id', $myId)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($myId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        if ($request->has('product_id')) {
            $product = Product::with('shop.owner')->find($request->input('product_id'));

            if ($product){
                $autoMessage = "Halo, saya tertarik dengan produk '{$product->product_name}' dalam toko '{$product->shop->shop_name}' dengan harga Rp " . number_format($product->price, 0, ',', '.') . ". Apakah masih tersedia?";
            }
        }
        $param['messages'] = $messages;
        $param['otherUser'] = $otherUser;
        $param['categories'] = $categories;

        if (Auth::user()->role == 'seller') {
            return view('pages.seller.chat', $param);
        }

        $viewName = (Auth::user()->role == 'seller') ? 'pages.seller.chat' : 'pages.user.chat';

        return view($viewName, compact('messages', 'otherUser', 'product', 'autoMessage', 'categories'));
    }

    public function store(Request $request, $receiverId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::user()->user_id,
            'receiver_id' => $receiverId,
            'content' => $validated['content'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'Message Sent!',
            'message' => $message,
        ]);
    }
}

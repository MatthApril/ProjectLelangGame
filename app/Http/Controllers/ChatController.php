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
    public function index(){
        $myId = Auth::user()->user_id;

        $chatPartners = Message::where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->get()
            ->map(function ($message) use ($myId) {
                return $message->sender_id === $myId ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->values();

        $users = User::whereIn('user_id', $chatPartners)->get();

        return view('pages.chat.index', compact('users'));  // Use shared view
    }

    public function open(Request $request, $userId){
        $myId = Auth::user()->user_id;
        $otherUser = User::findOrFail($userId);

        $product = null;
        $autoMessage = '';

        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Capture where the user came from
        $returnUrl = $request->input('return_url', route('chat.index'));
        $returnLabel = $request->input('return_label', 'Daftar Chat');

        $messages = Message::where(function($query) use ($myId, $userId) {
            $query->where('sender_id', $myId)->where('receiver_id', $userId);
        })->orWhere(function($query) use ($myId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        if ($request->has('product_id')) {
            $product = Product::with('shop.owner')->find($request->input('product_id'));

            if ($product) {
                $autoMessage = "Halo, saya tertarik dengan produk '{$product->product_name}' dalam toko '{$product->shop->shop_name}' dengan harga Rp " . number_format($product->price, 0, ',', '.') . ". Apakah masih tersedia?";
            }
        }

        return view('pages.chat.show', compact('messages', 'otherUser', 'product', 'autoMessage', 'returnUrl', 'returnLabel'));
    }

    public function store(Request $request, $receiverId){
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

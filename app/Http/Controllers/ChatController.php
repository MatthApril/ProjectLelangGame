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
    const ADMIN_ID = 1;

    public function index(Request $request){
        $myId = Auth::user()->user_id;
        $search = $request->input('search');

        $chatPartners = Message::where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->get()
            ->map(function ($message) use ($myId) {
                return $message->sender_id === $myId ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->filter(fn($id) => $id != self::ADMIN_ID) // Exclude admin from chat list
            ->values();

        $users = User::whereIn('user_id', $chatPartners)->get()->map(function ($user) use ($myId, $search) {
            $user->unread_count = Message::where('sender_id', $user->user_id)
                ->where('receiver_id', $myId)
                ->where('is_read', false)
                ->count();

            $user->last_message = Message::where(function($query) use ($myId, $user) {
                $query->where('sender_id', $myId)->where('receiver_id', $user->user_id);
            })->orWhere(function($query) use ($myId, $user) {
                $query->where('sender_id', $user->user_id)->where('receiver_id', $myId);
            })->orderBy('created_at', 'desc')->first();

            if ($search && stripos($user->username, $search) === false) {
                return null;
            }
            return $user;
        })->filter()->sortByDesc(function ($user) {
            return $user->last_message ? $user->last_message->created_at : null;
        });

        $notFound = $users->isEmpty() && $search;

        return view('pages.chat.index', compact('users', 'notFound'));
    }

    public function getChats(Request $request){
        $myId = Auth::user()->user_id;
        $search = $request->input('search');

        $chatPartners = Message::where('sender_id', $myId)
            ->orWhere('receiver_id', $myId)
            ->get()
            ->map(function ($message) use ($myId) {
                return $message->sender_id === $myId ? $message->receiver_id : $message->sender_id;
            })
            ->unique()
            ->filter(fn($id) => $id != self::ADMIN_ID) // Exclude admin from chat list
            ->values();

        $users = User::whereIn('user_id', $chatPartners)->get()->map(function ($user) use ($myId, $search) {
            $user->unread_count = Message::where('sender_id', $user->user_id)
                ->where('receiver_id', $myId)
                ->where('is_read', false)
                ->count();

            $lastMessage = Message::where(function($query) use ($myId, $user) {
                $query->where('sender_id', $myId)->where('receiver_id', $user->user_id);
            })->orWhere(function($query) use ($myId, $user) {
                $query->where('sender_id', $user->user_id)->where('receiver_id', $myId);
            })->orderBy('created_at', 'desc')->first();

            $user->last_message = $lastMessage;
            $user->last_message_content = $lastMessage ? $lastMessage->content : null;
            $user->last_message_time = $lastMessage ? $lastMessage->created_at->diffForHumans() : null;
            $user->last_message_is_mine = $lastMessage ? $lastMessage->sender_id == $myId : false;

            if ($search && stripos($user->username, $search) === false) {
                return null;
            }
            return $user;
        })->filter()->sortByDesc(function ($user) {
            return $user->last_message ? $user->last_message->created_at : null;
        })->values();

        return response()->json([
            'users' => $users,
            'myId' => $myId
        ]);
    }

    public function open(Request $request, $userId){
        $myId = Auth::user()->user_id;
        $otherUser = User::findOrFail($userId);

        if (!$otherUser) {
            return redirect()->route('chat.index')->with('error', 'Pengguna tidak ditemukan.');
        }

        if ($otherUser->user_id == $myId) {
            return redirect()->back()->with('error', 'Anda tidak dapat membuka chat dengan diri sendiri.');
        }

        $product = null;
        $autoMessage = '';

        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

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

        return view('pages.chat.show', compact('messages', 'otherUser', 'product', 'autoMessage'));
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

<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Category;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    function show($userId) {
        $myId = Auth::user()->user_id;
        $otherUser = User::findOrFail($userId);
        $otherUserId = $userId;
        $categories = Category::orderBy('category_name')->get();

        $messages = Message::where(function($query) use ($myId, $otherUserId) {
            $query->where('sender_id', $myId)
                  ->where('receiver_id', $otherUserId);
        })->orWhere(function($query) use ($myId, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                  ->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();

        $param['messages'] = $messages;
        $param['otherUser'] = $otherUser;
        $param['categories'] = $categories;

        if (Auth::user()->role == 'seller') {
            return view('pages.seller.chat', $param);
        }

        return view('pages.user.chat', $param);
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

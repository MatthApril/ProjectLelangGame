<?php

namespace App\Http\Controllers;

use App\Models\NotificationRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    function show(){
        $userId = Auth::user()->user_id;

        $recipients = NotificationRecipient::where('user_id', $userId)
            ->with('notification') // Load the related notification content
            ->orderBy('notif_recip_id', 'desc')
            ->get();

        $viewName = match(Auth::user()->role) {
            'seller' => 'pages.seller.notifications',
            default  => 'pages.user.notifications',
        };

        return view($viewName, compact('recipients'));
    }

    public function showDetail($id)
    {
        $userId = Auth::user()->user_id;

        $recipient = NotificationRecipient::where('user_id', $userId)
            ->where('notification_id', $id)
            ->with('notification') // Load the content
            ->firstOrFail(); // Returns 404 page if not found

        if ($recipient->is_read == 0) {
            $recipient->is_read = 1;

            $recipient->save();
        }

        $notification = $recipient->notification;

        $viewName = match(Auth::user()->role) {
            'seller' => 'pages.seller.notification_detail',
            default  => 'pages.user.notification_detail',
        };

        return view($viewName, compact('notification', 'recipient'));
    }

    public function destroy($id)
    {
        $recipient = NotificationRecipient::where('user_id', Auth::user()->user_id)
                        ->where('notif_recip_id', $id)
                        ->firstOrFail();

        $recipient->delete();

        return response()->json(['success' => true]);
    }
}

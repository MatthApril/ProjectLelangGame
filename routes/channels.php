<?php

use App\Models\SupportTickets;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('chat.{smallerId}.{largerId}', function ($user, $smallerId, $largerId) {
    $userId = (int) $user->user_id;
    return $userId === (int) $smallerId || $userId === (int) $largerId;
});

Broadcast::channel('support.ticket.{ticketId}', function ($user, $ticketId) {
    $ticket = SupportTickets::find($ticketId);

    if (!$ticket) {
        return false;
    }

    // Allow access if user is the ticket owner or admin (user_id = 1)
    return $user->user_id === $ticket->user_id || $user->user_id === 1;
});

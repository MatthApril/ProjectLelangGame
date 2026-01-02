<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('chat.{smallerId}.{largerId}', function ($user, $smallerId, $largerId) {
    $userId = (int) $user->user_id;
    $authorized = $userId === (int) $smallerId || $userId === (int) $largerId;
    Log::info('Channel auth', [
        'channel' => "chat.{$smallerId}.{$largerId}",
        'user_id' => $userId,
        'authorized' => $authorized
    ]);
    return $authorized;
});

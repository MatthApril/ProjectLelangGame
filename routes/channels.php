<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

Broadcast::channel('chat.{smallerId}.{largerId}', function ($user, $smallerId, $largerId) {
    $userId = (int) $user->user_id;
    return $userId === (int) $smallerId || $userId === (int) $largerId;
});

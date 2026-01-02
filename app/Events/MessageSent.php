<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public array $messageData;
    public int $senderId;
    public int $receiverId;
    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->senderId = $message->sender_id;
        $this->receiverId = $message->receiver_id;
        $this->messageData = [
            'message_id' => $message->message_id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'content' => $message->content,
            'created_at' => $message->created_at->toISOString(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $smallerId = min($this->senderId, $this->receiverId);
        $largerId  = max($this->senderId, $this->receiverId);

        return [
            new PrivateChannel('chat.' . $smallerId . '.' . $largerId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->messageData,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}

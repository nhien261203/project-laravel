<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnreadCountUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $conversationId;
    public $unreadCount;
    public $recipientType; // 'admin' hoặc 'user'
    public $connection = 'sync';

    public function __construct($conversationId, $unreadCount, $recipientType)
    {
        $this->conversationId = $conversationId;
        $this->unreadCount = $unreadCount;
        $this->recipientType = $recipientType;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // Kênh realtime riêng cho admin và user
        return new Channel('unread-count');
    }

    public function broadcastAs()
    {
        return 'unread.count.updated';
    }
}

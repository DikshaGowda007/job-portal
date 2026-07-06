<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/*
 * Fired after messages are marked as read.
 * Tells the sender that their messages were seen (read receipts).
 */
class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $applicationId,
        public int $readByUserId,
        public array $messageIds,
        public string $readAt
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.'.$this->applicationId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }

    public function broadcastWith(): array
    {
        return [
            'application_id' => $this->applicationId,
            'read_by_user_id' => $this->readByUserId,
            'message_ids' => $this->messageIds,
            'read_at' => $this->readAt,
        ];
    }
}

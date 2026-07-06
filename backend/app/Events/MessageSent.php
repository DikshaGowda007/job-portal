<?php

namespace App\Events;

use App\Models\ApplicationMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/*
 * ShouldBroadcast — queued broadcast.
 * The HTTP response returns immediately after saving to DB.
 * The broadcast is pushed to Reverb via the queue in the background.
 * This is fine because the sender already sees the message via optimistic UI.
 */
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ApplicationMessage $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.'.$this->message->application_id),
        ];
    }

    // Cleaner event name on the frontend: listen for "message.sent"
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        // SerializesModels re-hydrates $this->message from DB when the queued
        // job runs, so we must explicitly load the relationship here.
        $this->message->loadMissing('sender');

        // dd($this->message);
        return [
            'id' => $this->message->id,
            'application_id' => $this->message->application_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender
                ? trim($this->message->sender->first_name.' '.$this->message->sender->last_name)
                : '',
            'message' => $this->message->message,
            'read_at' => $this->message->read_at?->toISOString(),
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}

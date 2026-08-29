<?php

namespace App\Events;

use App\Models\UserNotification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public UserNotification $notification) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.User.' . $this->notification->user_id)];
    }

    public function broadcastAs(): string { return 'notification.created'; }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'type' => $this->notification->type,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'created_at' => $this->notification->created_at->diffForHumans(),
            'is_read' => (bool) $this->notification->is_read,
        ];
    }
}

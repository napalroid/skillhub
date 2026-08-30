<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public static function createAndDispatch(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $extraData = []
    ): UserNotification {
        $notification = UserNotification::create(array_merge([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ], $extraData));
        
        try {
            event(new NotificationCreated($notification));
        } catch (\Exception $e) {
            Log::error('Failed to broadcast notification', [
                'notification_id' => $notification->id,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }
        
        return $notification;
    }
}

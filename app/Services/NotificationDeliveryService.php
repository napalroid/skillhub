<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\Log;

class NotificationDeliveryService
{
    public static function trackSent(UserNotification $notification)
    {
        $notification->update([
            'delivered_at' => now(),
        ]);
    }

    public static function markDelivered(UserNotification $notification)
    {
        $notification->update([
            'ack_received_at' => now(),
            'retry_count' => 0,
        ]);
    }

    public static function recordRetry(UserNotification $notification)
    {
        $notification->increment('retry_count');
        $notification->update([
            'last_retry_at' => now(),
        ]);
    }

    public static function getPendingDeliveries(int $userId)
    {
        return UserNotification::where('user_id', $userId)
            ->whereNotNull('delivered_at')
            ->whereNull('ack_received_at')
            ->where('created_at', '<', now()->subSeconds(10))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getUndeliveredNotifications(int $userId)
    {
        return UserNotification::where('user_id', $userId)
            ->whereNull('delivered_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getRetryCandidates(int $maxRetries = 3, int $secondsAfterSend = 10)
    {
        return UserNotification::whereNotNull('delivered_at')
            ->whereNull('ack_received_at')
            ->where('retry_count', '<', $maxRetries)
            ->where('delivered_at', '<', now()->subSeconds($secondsAfterSend))
            ->orderBy('delivered_at', 'asc')
            ->get();
    }

    public static function markForResend(UserNotification $notification)
    {
        $notification->update([
            'delivered_at' => null,
            'retry_count' => 0,
        ]);
    }
}

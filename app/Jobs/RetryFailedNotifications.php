<?php

namespace App\Jobs;

use App\Models\UserNotification;
use App\Services\NotificationDeliveryService;
use App\Events\NotificationCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetryFailedNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->queue = 'notifications';
    }

    public function handle(): void
    {
        $retryCandidates = NotificationDeliveryService::getRetryCandidates(3, 10);

        $processed = 0;

        foreach ($retryCandidates as $notification) {
            try {
                $notification->refresh();

                if ($notification->ack_received_at) {
                    continue;
                }

                Log::info('Resending failed notification', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'retry_count' => $notification->retry_count,
                ]);

                event(new NotificationCreated($notification));

                NotificationDeliveryService::recordRetry($notification);

                $processed++;

                if ($notification->retry_count >= 3) {
                    Log::warning('Max retries reached for notification', [
                        'notification_id' => $notification->id,
                        'user_id' => $notification->user_id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to retry notification', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($processed > 0) {
            Log::info("Notification retry job completed. Resent {$processed} notifications.");
        }
    }
}

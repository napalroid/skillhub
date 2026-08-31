<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\NotificationService;

$userId = (int) ($argv[1] ?? 1);

echo "Creating test notification for user ID: {$userId}\n";

try {
    $notification = NotificationService::createAndDispatch(
        userId: $userId,
        type: 'test',
        title: '🧪 Test Real-time Notification',
        message: 'Ini adalah test notifikasi real-time. Jika kamu melihat ini tanpa refresh, WebSocket berhasil!',
        extraData: []
    );
    
    echo "✅ Notification created successfully!\n";
    echo "ID: {$notification->id}\n";
    echo "User ID: {$notification->user_id}\n";
    echo "Title: {$notification->title}\n";
    echo "\nCek browser sekarang - notifikasi harus muncul TANPA refresh!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

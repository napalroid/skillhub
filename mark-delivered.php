<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Marking all existing notifications as delivered...\n";

$updated = DB::table('user_notifications')
    ->whereNull('delivered_at')
    ->update([
        'delivered_at' => now(),
        'ack_received_at' => now(),
    ]);

echo "✅ Updated {$updated} notifications.\n";

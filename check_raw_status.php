<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = DB::table('orders')->select('id', 'status')->get();
echo "=== RAW ORDER STATUSES ===\n";
foreach ($orders as $order) {
    $len = strlen($order->status);
    echo "ID: {$order->id}, Status: '{$order->status}' (length: {$len})\n";
}

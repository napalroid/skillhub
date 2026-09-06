<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = \App\Models\Order::all(['id', 'status']);
echo "=== ALL ORDER STATUSES ===\n";
$statusCounts = [];
foreach ($orders as $order) {
    $status = $order->status;
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
    if ($order->id <= 10 || $order->id == 38) {
        echo "Order #{$order->id}: {$status}\n";
    }
}

echo "\n=== STATUS SUMMARY ===\n";
foreach ($statusCounts as $status => $count) {
    echo "{$status}: {$count} orders\n";
}

echo "\n=== CHECKING FOR INVALID STATUSES ===\n";
$validStatuses = ['menunggu_pembayaran', 'menunggu_verifikasi', 'dibayar', 'dikerjakan', 'menunggu_persetujuan', 'selesai', 'dibatalkan'];
foreach ($orders as $order) {
    if (!in_array($order->status, $validStatuses)) {
        echo "⚠ Order #{$order->id} has INVALID status: '{$order->status}'\n";
    }
}

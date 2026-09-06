<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::with(['payment', 'service'])->find(38);
echo "=== ORDER 38 CHECK ===\n";
echo "Order ID: {$order->id}\n";
echo "Status: {$order->status}\n";
echo "Payment exists: " . ($order->payment ? 'YES' : 'NO') . "\n";
echo "Payment Status: " . ($order->payment ? $order->payment->status : 'NULL') . "\n";
echo "Admin confirmed at: " . ($order->payment->admin_confirmed_at ?? 'NULL') . "\n";
echo "Is admin confirmed: " . ($order->payment->isAdminConfirmed() ? 'YES' : 'NO') . "\n";
echo "Can be started (canBeStartedBySeller): " . ($order->canBeStartedBySeller() ? 'YES' : 'NO') . "\n";

echo "\n=== VALIDATION CHECK ===\n";
echo "✓ Status is 'dibayar': " . ($order->status === 'dibayar' ? 'PASS' : 'FAIL') . "\n";
echo "✓ Payment exists: " . ($order->payment ? 'PASS' : 'FAIL') . "\n";
echo "✓ Admin confirmed: " . ($order->payment && $order->payment->isAdminConfirmed() ? 'PASS' : 'FAIL') . "\n";

echo "\nButton 'Mulai Kerjakan' should work: ";
if ($order->status === 'dibayar' && $order->payment && $order->payment->isAdminConfirmed()) {
    echo "YES\n";
} else {
    echo "NO - Payment not confirmed by admin yet\n";
}

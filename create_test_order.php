<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CREATING TEST ORDER ===\n";

$seller = \App\Models\User::where('role', 'seller')->first();
if (!$seller) {
    die("ERROR: No seller found. Run seeder first.\n");
}

$buyer = \App\Models\User::where('role', 'buyer')->first();
if (!$buyer) {
    die("ERROR: No buyer found. Run seeder first.\n");
}

$service = \App\Models\Service::first();
if (!$service) {
    die("ERROR: No service found. Run seeder first.\n");
}

echo "Seller: {$seller->email}\n";
echo "Buyer: {$buyer->email}\n";
echo "Service: {$service->title}\n\n";

$order = \App\Models\Order::create([
    'service_id' => $service->id,
    'buyer_id' => $buyer->id,
    'status' => 'menunggu_konfirmasi',
    'payment_status' => 'paid',
    'final_price' => 100000,
    'paid_at' => now(),
]);

$payment = \App\Models\Payment::create([
    'order_id' => $order->id,
    'amount' => 100000,
    'status' => 'paid',
    'payment_type' => 'qris',
    'gateway_transaction_id' => 'TEST-' . uniqid(),
]);

echo "✓ Order created: ID #{$order->id}\n";
echo "✓ Status: {$order->status}\n";
echo "✓ Payment Status: {$payment->status}\n";
echo "✓ Admin confirmed: " . ($payment->isAdminConfirmed() ? 'YES' : 'NO') . "\n";
echo "\n=== SIMULATING ADMIN CONFIRMATION ===\n";

$payment->update([
    'admin_confirmed_at' => now(),
    'admin_confirmed_by' => 1,
    'status' => 'verified',
]);

$order->update(['status' => 'dikonfirmasi']);

echo "✓ Payment confirmed by admin\n";
echo "✓ Order status updated to: {$order->fresh()->status}\n";
echo "\nTest order #{$order->id} ready for testing!\n";
echo "Login as seller: {$service->user->email} / password123\n";
echo "Visit: /pesanan/{$order->id}\n";

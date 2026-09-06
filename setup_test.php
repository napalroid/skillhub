<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== UPDATING USER ROLES ===\n";

$seller = \App\Models\User::where('email', 'seller@example.com')->first();
if ($seller) {
    $seller->update(['role' => 'seller']);
    echo "✓ seller@example.com updated to seller role\n";
}

$buyer = \App\Models\User::where('email', 'buyer@example.com')->first();
if ($buyer) {
    $buyer->update(['role' => 'buyer']);
    echo "✓ buyer@example.com updated to buyer role\n";
}

echo "\n=== CREATING TEST ORDER ===\n";

$service = \App\Models\Service::with('seller')->first();
if (!$service) {
    die("ERROR: No service found.\n");
}

if (!$service->seller) {
    echo "Service has no seller, assigning to seller...\n";
    $service->update(['user_id' => $seller->id]);
    $service->refresh();
}

echo "Service: {$service->title}\n";
echo "Service Owner: {$service->seller->email}\n\n";

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
echo "✓ Status: {$order->status} (waiting for admin confirmation)\n";
echo "✓ Payment Status: {$payment->status}\n";
echo "✓ Admin confirmed: NO\n";

echo "\n=== TEST SCENARIO 1: Order in 'menunggu_konfirmasi' ===\n";
echo "Login as seller: {$service->seller->email} / password123\n";
echo "Visit: http://localhost/pesanan/{$order->id}\n";
echo "Expected: Should see yellow banner 'Menunggu konfirmasi admin', NO 'Mulai Kerjakan' button\n";

echo "\n=== SIMULATING ADMIN CONFIRMATION ===\n";

$payment->update([
    'admin_confirmed_at' => now(),
    'admin_confirmed_by' => 1,
    'status' => 'verified',
]);

$order->update(['status' => 'dikonfirmasi']);

echo "✓ Payment confirmed by admin\n";
echo "✓ Order status updated to: dikonfirmasi\n";

echo "\n=== TEST SCENARIO 2: Order in 'dikonfirmasi' ===\n";
echo "Refresh page: http://localhost/pesanan/{$order->id}\n";
echo "Expected: Should see green banner 'Pembayaran sudah dikonfirmasi', 'Mulai Kerjakan' button visible\n";
echo "Click 'Mulai Kerjakan' → status should change to 'dikerjakan'\n";
echo "Then 'Upload Hasil' form should be available\n";

echo "\n✅ Test order #{$order->id} ready!\n";

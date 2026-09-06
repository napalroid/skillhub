<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payment = \App\Models\Payment::where('order_id', 38)->first();

echo "=== BEFORE CONFIRMATION ===\n";
echo "Payment ID: {$payment->id}\n";
echo "Status: {$payment->status}\n";
echo "Admin confirmed at: " . ($payment->admin_confirmed_at ?? 'NULL') . "\n";

$payment->update([
    'admin_confirmed_at' => now(),
    'admin_confirmed_by' => 1
]);

$payment->refresh();

echo "\n=== AFTER CONFIRMATION ===\n";
echo "Admin confirmed at: {$payment->admin_confirmed_at}\n";
echo "Is admin confirmed: " . ($payment->isAdminConfirmed() ? 'YES' : 'NO') . "\n";

echo "\n✓ Payment berhasil dikonfirmasi admin!\n";
echo "Sekarang seller bisa klik 'Mulai Kerjakan'\n";

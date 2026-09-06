<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== USERS ===\n";
echo "Total users: " . \App\Models\User::count() . "\n";
echo "Admins: " . \App\Models\User::where('role', 'admin')->count() . "\n";
echo "Sellers: " . \App\Models\User::where('role', 'seller')->count() . "\n";
echo "Buyers: " . \App\Models\User::where('role', 'buyer')->count() . "\n\n";

echo "=== SERVICES ===\n";
echo "Total Services: " . \App\Models\Service::count() . "\n";
$service = \App\Models\Service::with(['seller', 'subcategory.category'])->first();
if ($service) {
    echo "Title: {$service->title}\n";
    echo "Price: Rp " . number_format($service->price, 0, ',', '.') . "\n";
    echo "Owner: {$service->seller->name} ({$service->seller->email})\n";
    echo "Category: {$service->subcategory->category->name} → {$service->subcategory->name}\n";
    echo "Status: {$service->status}\n";
    echo "Description: " . substr($service->description, 0, 100) . "...\n";
}

echo "\n=== TIME SLOTS ===\n";
$totalSlots = \App\Models\ServiceTimeSlot::count();
echo "Total Slots: {$totalSlots}\n";
if ($totalSlots > 0) {
    $firstSlot = \App\Models\ServiceTimeSlot::first();
    echo "Max Bookings per Slot: {$firstSlot->max_bookings}\n";
    echo "First Slot: {$firstSlot->date} {$firstSlot->time_start}-{$firstSlot->time_end}\n";
    $lastSlot = \App\Models\ServiceTimeSlot::orderBy('date', 'desc')->first();
    echo "Last Slot: {$lastSlot->date} {$lastSlot->time_start}-{$lastSlot->time_end}\n";
}

echo "\n=== ORDERS ===\n";
echo "Total Orders: " . \App\Models\Order::count() . "\n";

echo "\n=== CATEGORIES ===\n";
$categories = \App\Models\Category::with('subcategories')->get();
foreach ($categories as $cat) {
    echo "- {$cat->name} ({$cat->subcategories->count()} subcategories)\n";
    foreach ($cat->subcategories as $sub) {
        echo "  → {$sub->name}\n";
    }
}

echo "\n✅ VERIFICATION COMPLETE!\n";

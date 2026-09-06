<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$slots = \App\Models\ServiceTimeSlot::where('service_id', 1)
    ->whereDate('date', '>=', now()->format('Y-m-d'))
    ->orderBy('date')
    ->orderBy('time_start')
    ->get()
    ->map(fn($s) => [
        'id' => $s->id,
        'date' => $s->date->format('Y-m-d'),
        'time_start' => $s->time_start,
        'time_end' => $s->time_end,
        'max_bookings' => $s->max_bookings,
        'platform_bookings' => $s->orders()->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])->count(),
        'available_count' => $s->getAvailableCountAttribute(),
    ]);

echo json_encode($slots->toArray(), JSON_PRETTY_PRINT);

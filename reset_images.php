<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update services to use image placeholder services
$placeholders = [
    'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?w=400&h=300&fit=crop&q=80',  // design
    'https://images.unsplash.com/photo-1555099962-4199c345e5dd?w=400&h=300&fit=crop&q=80',  // code
    'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=400&h=300&fit=crop&q=80',  // video
    'https://images.unsplash.com/photo-1492633423870-43d1cd2775b5?w=400&h=300&fit=crop&q=80',  // photo
];

$services = App\Models\Service::all();
$idx = 0;
foreach ($services as $service) {
    $service->image = null; // Reset to null, will use placeholder
    $service->save();
    echo "Reset service #{$service->id}" . PHP_EOL;
}

echo "Done! All services reset." . PHP_EOL;

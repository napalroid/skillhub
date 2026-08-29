<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Use existing stock images from public folder
$sampleImages = [
    'samples/design-1.jpg',
    'samples/design-2.jpg',
    'samples/code-1.jpg',
    'samples/video-1.jpg',
    'samples/photo-1.jpg',
];

// Copy sample images to storage
$sourceDir = public_path('images');
$storageApp = storage_path('app/public/services');
if (!is_dir($storageApp)) mkdir($storageApp, 0755, true);

// Check what images exist
echo "Source images: " . PHP_EOL;
$files = glob($sourceDir . '/*');
foreach ($files as $f) {
    echo basename($f) . PHP_EOL;
}

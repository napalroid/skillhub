<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create sample images using SVG converted to files
$storageApp = storage_path('app/public/services');
if (!is_dir($storageApp)) mkdir($storageApp, 0755, true);

// Generate sample service images using simple gradients and text
$samples = [
    ['name' => 'sample-design-1.svg', 'title' => 'Design', 'color1' => '#111111', 'color2' => '#444444'],
    ['name' => 'sample-design-2.svg', 'title' => 'Creative', 'color1' => '#222222', 'color2' => '#666666'],
    ['name' => 'sample-code-1.svg', 'title' => 'Code', 'color1' => '#000000', 'color2' => '#333333'],
    ['name' => 'sample-video-1.svg', 'title' => 'Video', 'color1' => '#1a1a1a', 'color2' => '#555555'],
    ['name' => 'sample-photo-1.svg', 'title' => 'Photo', 'color1' => '#0a0a0a', 'color2' => '#3a3a3a'],
    ['name' => 'sample-music-1.svg', 'title' => 'Music', 'color1' => '#1c1c1c', 'color2' => '#4a4a4a'],
    ['name' => 'sample-writing-1.svg', 'title' => 'Writing', 'color1' => '#0d0d0d', 'color2' => '#404040'],
    ['name' => 'sample-business-1.svg', 'title' => 'Business', 'color1' => '#1f1f1f', 'color2' => '#5a5a5a'],
];

foreach ($samples as $s) {
    $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300" width="400" height="300">
  <defs>
    <linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="' . $s['color1'] . '"/>
      <stop offset="100%" stop-color="' . $s['color2'] . '"/>
    </linearGradient>
    <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
      <circle cx="2" cy="2" r="1" fill="white" opacity="0.1"/>
    </pattern>
  </defs>
  <rect width="400" height="300" fill="url(#g)"/>
  <rect width="400" height="300" fill="url(#dots)"/>
  <text x="200" y="160" font-family="Arial, sans-serif" font-size="48" font-weight="900" fill="white" text-anchor="middle" opacity="0.9">' . $s['title'] . '</text>
  <text x="200" y="200" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="white" text-anchor="middle" opacity="0.5" letter-spacing="3">SKILLHUB</text>
</svg>';

    file_put_contents($storageApp . '/' . $s['name'], $svg);
}

// Update some services to use these images
$imageFiles = array_column($samples, 'name');
$services = App\Models\Service::all();
$idx = 0;
foreach ($services as $service) {
    $imageName = $imageFiles[$idx % count($imageFiles)];
    $service->image = 'services/' . $imageName;
    $service->save();
    $idx++;
    echo "Updated service #{$service->id}: {$imageName}" . PHP_EOL;
}

echo "Done! " . $services->count() . " services updated." . PHP_EOL;

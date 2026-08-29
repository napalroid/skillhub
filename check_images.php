<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$services = App\Models\Service::take(10)->get();
foreach ($services as $s) {
    echo $s->id . ': ' . ($s->image ?? 'NO IMAGE') . " | title: " . $s->title . PHP_EOL;
}
echo "Total services with image: " . App\Models\Service::whereNotNull('image')->where('image', '!=', '')->count() . PHP_EOL;

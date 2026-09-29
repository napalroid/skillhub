<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$services = collect();
$categories = collect();
$subcategories = collect();

$html = view('services.my-services', compact('services', 'categories', 'subcategories'))->render();

if (strpos($html, 'seller-workspace') !== false) {
    echo '✓ NEW DESIGN DETECTED in view rendering';
} elseif (strpos($html, 'service-workspace') !== false) {
    echo '✗ OLD DESIGN STILL RENDERING in view';
} else {
    echo '? NO DESIGN PATTERN FOUND';
}

$kernel->terminate($request, $response);
?>
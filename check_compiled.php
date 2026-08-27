<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$compiler = app('blade.compiler');
$path = resource_path('views/conversations/index.blade.php');
$compiled = $compiler->getCompiledPath($path);
$compiler->compile($path);
$lines = explode("\n", file_get_contents($compiled));
foreach ($lines as $i => $l) {
    if (preg_match('/else|elseif|endif/', $l) || preg_match('/\bif\s*\(/', $l)) {
        printf("%d: %s\n", $i+1, rtrim($l));
    }
}

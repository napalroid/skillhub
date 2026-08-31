<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = \App\Models\User::select('id', 'name', 'email')->limit(5)->get();

echo "Available Users:\n";
echo "================\n";
foreach($users as $u) {
    echo "ID: {$u->id} - {$u->name} ({$u->email})\n";
}

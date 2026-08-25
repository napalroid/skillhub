<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pencairan escrow otomatis (1 jam setelah selesai) + auto-complete (3 hari)
Schedule::command('orders:release-due')->everyMinute();

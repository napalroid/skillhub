<?php

namespace App\Console\Commands;

use App\Jobs\RetryFailedNotifications;
use Illuminate\Console\Command;

class RetryNotifications extends Command
{
    protected $signature = 'retry:notifications';

    protected $description = 'Retry failed notification deliveries.';

    public function handle(): int
    {
        $this->info('Starting notification retry process...');

        RetryFailedNotifications::dispatch();

        $this->info('Retry job dispatched successfully.');

        return self::SUCCESS;
    }
}

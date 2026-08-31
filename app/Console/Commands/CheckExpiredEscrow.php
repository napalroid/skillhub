<?php

namespace App\Console\Commands;

use App\Services\EscrowService;
use Illuminate\Console\Command;

class CheckExpiredEscrow extends Command
{
    protected $signature = 'escrow:check-expired';

    protected $description = 'Proses transaksi escrow yang sudah melewati batas waktu 24 jam';

    public function handle(EscrowService $escrowService): int
    {
        $this->info('Checking for expired escrow transactions...');

        $count = $escrowService->expireTransactions();

        if ($count > 0) {
            $this->info("{$count} transaksi escrow expired telah diproses.");
        } else {
            $this->info('Tidak ada transaksi escrow yang expired.');
        }

        return self::SUCCESS;
    }
}

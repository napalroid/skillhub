<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remove seller alerts created before an order was paid. They represent
     * abandoned checkout attempts, not actionable seller work.
     */
    public function up(): void
    {
        DB::table('user_notifications')
            ->where('type', 'new_order')
            ->whereIn('order_id', function ($query) {
                $query->select('id')
                    ->from('orders')
                    ->where('status', Order::STATUS_MENUNGGU_PEMBAYARAN);
            })
            ->delete();
    }

    public function down(): void
    {
        // Deleted notifications are intentionally not recreated.
    }
};

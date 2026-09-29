<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Existing manual Joki ML services already have per-rank prices because the
     * previous form required them. Keep the data, but make their pricing rule
     * consistent with all newly-created Joki ML services.
     */
    public function up(): void
    {
        DB::table('joki_ml_services')
            ->where('pricing_mode', 'manual')
            ->update(['pricing_mode' => 'auto']);
    }

    public function down(): void
    {
        // This is an intentional business-rule migration. Restoring a manual
        // selection would require historical user intent that is not stored.
    }
};

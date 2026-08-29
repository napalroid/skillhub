<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categoryNames = [
            'Transportasi',
            'Tutor & Les',
            'Jasa Titip',
            'Courier & Antar Jemput',
            'Konten & Media'
        ];

        $categoryIds = DB::table('categories')->whereIn('name', $categoryNames)->pluck('id')->toArray();

        if (!empty($categoryIds)) {
            $subcategoryIds = DB::table('subcategories')->whereIn('category_id', $categoryIds)->pluck('id')->toArray();

            if (!empty($subcategoryIds)) {
                DB::table('services')->whereIn('subcategory_id', $subcategoryIds)->delete();
            }

            DB::table('subcategories')->whereIn('category_id', $categoryIds)->delete();
            DB::table('categories')->whereIn('name', $categoryNames)->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

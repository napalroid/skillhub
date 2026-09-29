<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class CompleteServiceTypeSetupSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== Starting Complete Service Type Setup ===' . PHP_EOL);

        // Create Gaming Category & Joki ML Subcategory
        $gaming = Category::firstOrCreate(['name' => 'Gaming']);
        $this->command->info("✓ Gaming category ID: {$gaming->id}");

        $jokiMlType = ServiceType::where('code', 'joki_ml')->first();
        if ($jokiMlType) {
            Subcategory::firstOrCreate(
                ['category_id' => $gaming->id, 'name' => 'Joki Mobile Legends'],
                ['service_type_id' => $jokiMlType->id]
            );
            $this->command->info('✓ Joki Mobile Legends subcategory linked to joki_ml service type');
        }

        // Create Barber Category & Subcategory
        $beauty = Category::firstOrCreate(['name' => 'Kecantikan & Grooming']);
        $this->command->info("✓ Kecantikan & Grooming category ID: {$beauty->id}");

        $barberType = ServiceType::where('code', 'barber')->first();
        if ($barberType) {
            Subcategory::firstOrCreate(
                ['category_id' => $beauty->id, 'name' => 'Barber / Potong Rambut'],
                ['service_type_id' => $barberType->id]
            );
            $this->command->info('✓ Barber / Potong Rambut subcategory linked to barber service type');
        }

        // Create additional gaming subcategories
        Subcategory::firstOrCreate(['category_id' => $gaming->id, 'name' => 'Joki Valorant']);
        Subcategory::firstOrCreate(['category_id' => $gaming->id, 'name' => 'Joki Genshin Impact']);
        Subcategory::firstOrCreate(['category_id' => $gaming->id, 'name' => 'Joki Dota 2']);
        Subcategory::firstOrCreate(['category_id' => $gaming->id, 'name' => 'Joki Free Fire']);

        // Create additional beauty subcategories
        Subcategory::firstOrCreate(['category_id' => $beauty->id, 'name' => 'Manicure & Pedicure']);
        Subcategory::firstOrCreate(['category_id' => $beauty->id, 'name' => 'Perawatan Kulit']);
        Subcategory::firstOrCreate(['category_id' => $beauty->id, 'name' => 'Makeup Artist']);

        $this->command->info(PHP_EOL . '=== Complete Service Type Setup Finished ===' . PHP_EOL);
        $this->command->info('Run: php artisan app:verify-service-types to verify');
    }
}

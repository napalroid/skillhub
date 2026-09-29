<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class GamingCategorySeeder extends Seeder
{
    public function run(): void
    {
        $gaming = Category::create([
            'name' => 'Gaming',
        ]);

        $this->command->info("Category 'Gaming' created with ID: {$gaming->id}");

        $jokiMlType = ServiceType::where('code', 'joki_ml')->first();

        if (!$jokiMlType) {
            $this->command->error('Service type joki_ml not found. Please run ServiceTypeSeeder first.');
            return;
        }

        $jokiMl = Subcategory::create([
            'category_id' => $gaming->id,
            'name' => 'Joki Mobile Legends',
            'service_type_id' => $jokiMlType->id,
        ]);

        $this->command->info("Subcategory 'Joki Mobile Legends' created with ID: {$jokiMl->id}");

        Subcategory::create([
            'category_id' => $gaming->id,
            'name' => 'Joki Valorant',
            'service_type_id' => null,
        ]);

        Subcategory::create([
            'category_id' => $gaming->id,
            'name' => 'Joki Genshin Impact',
            'service_type_id' => null,
        ]);

        $this->command->info('Gaming category and subcategories seeded successfully!');
    }
}

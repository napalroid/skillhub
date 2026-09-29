<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class BarberCategorySeeder extends Seeder
{
    public function run(): void
    {
        $beauty = Category::create([
            'name' => 'Kecantikan & Grooming',
        ]);

        $this->command->info("Category 'Kecantikan & Grooming' created with ID: {$beauty->id}");

        $barberType = ServiceType::where('code', 'barber')->first();

        if (!$barberType) {
            $this->command->error('Service type barber not found. Please run ServiceTypeSeeder first.');
            return;
        }

        $barber = Subcategory::create([
            'category_id' => $beauty->id,
            'name' => 'Barber / Potong Rambut',
            'service_type_id' => $barberType->id,
        ]);

        $this->command->info("Subcategory 'Barber / Potong Rambut' created with ID: {$barber->id}");

        $manicure = Subcategory::create([
            'category_id' => $beauty->id,
            'name' => 'Manicure & Pedicure',
            'service_type_id' => null,
        ]);

        $skinCare = Subcategory::create([
            'category_id' => $beauty->id,
            'name' => 'Perawatan Kulit',
            'service_type_id' => null,
        ]);

        $this->command->info('Beauty category and subcategories seeded successfully!');
    }
}

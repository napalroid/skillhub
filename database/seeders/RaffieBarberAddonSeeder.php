<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class RaffieBarberAddonSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::query()
            ->where('title', 'Barber Raffie')
            ->whereHas('seller', fn ($query) => $query->where('name', 'Raffie'))
            ->first();

        if (! $service) {
            $this->command?->warn('Jasa Barber Raffie tidak ditemukan; data uji add-on dilewati.');
            return;
        }

        collect([
            ['name' => 'Creambath', 'description' => 'Perawatan rambut dan pijat kepala ringan.', 'price' => 25000, 'sort_order' => 1],
            ['name' => 'Cuci rambut', 'description' => 'Pembersihan rambut sebelum atau setelah potong.', 'price' => 10000, 'sort_order' => 2],
            ['name' => 'Hair tonic', 'description' => 'Aplikasi hair tonic setelah perawatan.', 'price' => 8000, 'sort_order' => 3],
        ])->each(function (array $addon) use ($service) {
            $service->addons()->updateOrCreate(
                ['name' => $addon['name']],
                $addon + ['is_active' => true],
            );
        });
    }
}

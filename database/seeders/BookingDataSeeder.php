<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class BookingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample booking config untuk jasa barber
        $barberConfig = [
            'enabled' => true,
            'fields' => [
                [
                    'name' => 'jenis_potongan',
                    'type' => 'select',
                    'label' => 'Jenis Potongan',
                    'required' => true,
                    'options' => ['Crewcut', 'Fade', 'Undercut', 'Pompadour', 'Layered', 'Buzzcut'],
                ],
                [
                    'name' => 'lama_estimasi',
                    'type' => 'number',
                    'label' => 'Estimasi Lama (menit)',
                    'required' => true,
                    'min' => 15,
                    'max' => 90,
                    'default' => 30,
                ],
                [
                    'name' => 'catatan_khusus',
                    'type' => 'textarea',
                    'label' => 'Catatan Khusus',
                    'required' => false,
                    'placeholder' => 'Alergi, preferensi khusus, dll...',
                ],
            ],
        ];

        // Sample booking config untuk jasa delivery
        $deliveryConfig = [
            'enabled' => true,
            'fields' => [
                [
                    'name' => 'alamat_pickup',
                    'type' => 'textarea',
                    'label' => 'Alamat Pick-up',
                    'required' => true,
                    'placeholder' => 'Jl. Contoh No. 10, Jakarta Pusat',
                ],
                [
                    'name' => 'alamat_dropoff',
                    'type' => 'textarea',
                    'label' => 'Alamat Drop-off',
                    'required' => true,
                    'placeholder' => 'Jl. Tujuan No. 5, Jakarta Selatan',
                ],
                [
                    'name' => 'jenis_barang',
                    'type' => 'select',
                    'label' => 'Jenis Barang',
                    'required' => true,
                    'options' => ['Dokumen', 'Makanan', 'Paket Kecil (<1kg)', 'Paket Sedang (1-5kg)', 'Paket Besar (>5kg)', 'Elektronik'],
                ],
                [
                    'name' => 'berat_perkiraan',
                    'type' => 'number',
                    'label' => 'Perkiraan Berat (kg)',
                    'required' => false,
                    'min' => 0.1,
                    'max' => 50,
                    'step' => 0.1,
                ],
            ],
        ];

        // Sample booking config untuk joki ML
        $mlJokiConfig = [
            'enabled' => true,
            'fields' => [
                [
                    'name' => 'rank_sekarang',
                    'type' => 'select',
                    'label' => 'Rank Sekarang',
                    'required' => true,
                    'options' => ['Warrior', 'Elite', 'Master', 'Grandmaster', 'Epic', 'Legend', 'Mythic', 'Mythic Glory'],
                ],
                [
                    'name' => 'rank_target',
                    'type' => 'select',
                    'label' => 'Rank yang Ingin Dicapai',
                    'required' => true,
                    'options' => ['Epic', 'Legend', 'Mythic', 'Mythic Glory'],
                ],
                [
                    'name' => 'username_ml',
                    'type' => 'text',
                    'label' => 'Username Akun ML',
                    'required' => true,
                ],
                [
                    'name' => 'password_akun',
                    'type' => 'password',
                    'label' => 'Password Akun',
                    'required' => true,
                    'help_text' => 'Password akan dienkripsi dan hanya booster yang bisa lihat',
                ],
            ],
        ];

        // Update services dengan booking config
        $services = Service::where('status', 'approved')->get();
        $count = 0;

        foreach ($services as $index => $service) {
            if ($index === 0) {
                $service->update(['booking_config' => $barberConfig]);
            } elseif ($index === 1) {
                $service->update(['booking_config' => $deliveryConfig]);
            } elseif ($index === 2) {
                $service->update(['booking_config' => $mlJokiConfig]);
            } else {
                continue;
            }
            $count++;
        }

        $this->command->info("✅ Updated booking config for $count services");
        $this->command->info("📦 Barber: 1 service");
        $this->command->info("📦 Delivery: 1 service");
        $this->command->info("📦 Joki ML: 1 service");
    }
}

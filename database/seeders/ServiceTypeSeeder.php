<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
ServiceType::create([
    'code' => 'joki_ml',
    'name' => 'Joki Mobile Legends',
    'icon' => '🎮',
    'description' => 'Jasa joki rank Mobile Legends dengan sistem perhitungan otomatis per bintang',
    'service_table' => 'joki_ml_services',
    'order_table' => 'joki_ml_orders',
    'service_model' => 'App\Models\JokiMlService',
    'order_model' => 'App\Models\JokiMlOrder',
    'has_custom_pricing' => true,
    'requires_manual_approval' => false,
    'hidden_from_listing' => false, // SUDAH DIPERBAIKI: service harus muncul di marketplace
    'enable_time_slots' => false,
    'is_active' => true,
]);

        ServiceType::create([
            'code' => 'barber',
            'name' => 'Potong Rambut / Barber',
            'icon' => '✂️',
            'description' => 'Jasa potong rambut profesional dengan berbagai pilihan model',
            'service_table' => 'barber_services',
            'order_table' => 'barber_orders',
            'service_model' => 'App\Models\BarberService',
            'order_model' => 'App\Models\BarberOrder',
            'has_custom_pricing' => false,
            'requires_manual_approval' => false,
            'hidden_from_listing' => false,
            'enable_time_slots' => true,
            'is_active' => true,
        ]);
    }
}

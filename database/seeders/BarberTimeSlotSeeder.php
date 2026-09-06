<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTimeSlot;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BarberTimeSlotSeeder extends Seeder
{
    public function run(): void
    {
        $barberService = Service::where('title', 'Barber Potong Raffie')
            ->where('status', 'approved')
            ->first();

        if (!$barberService) {
            $this->command->error('Barber Potong Raffie service not found');
            return;
        }

        $this->command->info('Creating time slots for: ' . $barberService->title);

        ServiceTimeSlot::where('service_id', $barberService->id)->delete();

        $slots = [];
        $startDate = Carbon::now();
        $endDate = $startDate->clone()->addMonths(3);

        $current = $startDate->clone();

        while ($current <= $endDate) {
            $dayOfWeek = $current->dayOfWeek;

            if ($dayOfWeek !== Carbon::SATURDAY && $dayOfWeek !== Carbon::SUNDAY) {
                for ($hour = 12; $hour < 21; $hour++) {
                    $timeStart = sprintf('%02d:00', $hour);
                    $timeEnd = sprintf('%02d:00', $hour + 1);

                    $slots[] = [
                        'service_id' => $barberService->id,
                        'date' => $current->format('Y-m-d'),
                        'time_start' => $timeStart,
                        'time_end' => $timeEnd,
                        'max_bookings' => 10,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            $current->addDay();
        }

        ServiceTimeSlot::insert($slots);

        $this->command->info('✅ Barber time slots created: ' . count($slots) . ' slots');
        $this->command->info('📅 Period: ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));
        $this->command->info('⏰ Time: 12:00 - 21:00 (Senin-Jumat)');
        $this->command->info('👥 Max bookings per slot: 10');
    }
}


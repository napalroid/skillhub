<?php

namespace App\Livewire\Booking;

use App\Models\Service;
use App\Models\ServiceTimeSlot;
use App\Models\TimeSlotHistory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TimeSlotManager extends Component
{
    public Service $service;
    public $selectedDate;
    public $newSlot = [
        'date' => '',
        'time_start' => '08:00',
        'time_end' => '09:00',
        'max_bookings' => 1,
    ];
    public $timeSlots = [];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadSlots();
    }

    public function loadSlots()
    {
        $this->timeSlots = ServiceTimeSlot::where('service_id', $this->service->id)
            ->whereDate('date', $this->selectedDate)
            ->orderBy('time_start')
            ->get();
    }

    public function changeDate($date)
    {
        $this->selectedDate = $date;
        $this->loadSlots();
    }

    public function addSlot()
    {
        $this->validate([
            'newSlot.date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'newSlot.time_start' => 'required|date_format:H:i',
            'newSlot.time_end' => 'required|date_format:H:i|after:' . $this->newSlot['time_start'],
            'newSlot.max_bookings' => 'required|integer|min:1|max:100',
        ]);

        DB::transaction(function () {
            ServiceTimeSlot::create([
                'service_id' => $this->service->id,
                'date' => $this->newSlot['date'],
                'time_start' => $this->newSlot['time_start'],
                'time_end' => $this->newSlot['time_end'],
                'max_bookings' => $this->newSlot['max_bookings'],
            ]);
        });

        $this->reset('newSlot');
        $this->newSlot = [
            'date' => '',
            'time_start' => '08:00',
            'time_end' => '09:00',
            'max_bookings' => 1,
        ];
        
        $this->loadSlots();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Slot waktu berhasil ditambahkan']);
    }

    public function removeSlot($slotId)
    {
        $slot = ServiceTimeSlot::find($slotId);

        if (!$slot) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Slot tidak ditemukan']);
            return;
        }

        $bookedCount = $slot->orders()
            ->whereIn('status', ['menunggu_konfirmasi', 'dikonfirmasi', 'dikerjakan', 'menunggu_persetujuan', 'selesai'])
            ->count();

        if ($bookedCount > 0) {
            $this->dispatch('alert', [
                'type' => 'error',
                'message' => "Tidak bisa hapus slot yang sudah ada booking-nya ({$bookedCount} booking)"
            ]);
            return;
        }

        $slot->delete();
        
        $this->loadSlots();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Slot waktu berhasil dihapus']);
    }

    public function getSlotsByDate()
    {
        return ServiceTimeSlot::where('service_id', $this->service->id)
            ->whereDate('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time_start')
            ->get();
    }

    public function render()
    {
        return view('livewire.booking.time-slot-manager');
    }
}

<?php

namespace App\Livewire\Booking;

use App\Models\Service;
use App\Models\ServiceTimeSlot;
use Livewire\Component;

class TimeSlotSelector extends Component
{
    public Service $service;

    public $selectedDate;

    public $selectedSlotId;

    public $slots = [];

    public $availableDates = [];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadSlots();
        $this->loadAvailableDates();
    }

    public function loadSlots()
    {
        $this->slots = ServiceTimeSlot::where('service_id', $this->service->id)
            ->whereDate('date', $this->selectedDate)
            ->whereRaw('max_bookings > (SELECT COUNT(*) FROM orders WHERE orders.time_slot_id = service_time_slots.id AND orders.status IN ("menunggu_konfirmasi", "dikonfirmasi", "dikerjakan", "menunggu_persetujuan", "selesai"))')
            ->orderBy('time_start')
            ->get();
    }

    public function loadAvailableDates()
    {
        $this->availableDates = ServiceTimeSlot::where('service_id', $this->service->id)
            ->whereDate('date', '>=', now()->format('Y-m-d'))
            ->groupBy('date')
            ->pluck('date')
            ->map(fn($date) => date('Y-m-d', strtotime($date)))
            ->values();
    }

    public function changeDate($date)
    {
        $this->selectedDate = $date;
        $this->selectedSlotId = null;
        $this->loadSlots();
    }

    public function selectSlot($slotId)
    {
        $this->selectedSlotId = $slotId;
        $this->dispatch('slotSelected', slotId: $slotId);
    }

    public function render()
    {
        return view('livewire.booking.time-slot-selector');
    }
}

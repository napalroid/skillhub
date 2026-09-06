<?php

namespace App\Livewire\Booking;

use App\Models\Order;
use App\Models\ServiceTimeSlot;
use App\Models\TimeSlotHistory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderTimeSlotEditor extends Component
{
    public Order $order;
    public $currentSlot;
    public $newSlotId;
    public $notes = '';
    public $slots = [];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->currentSlot = $order->timeSlot;
        $this->newSlotId = $order->time_slot_id;
        $this->loadAvailableSlots();
    }

    public function loadAvailableSlots()
    {
        $service = $this->order->service;
        
        $this->slots = ServiceTimeSlot::where('service_id', $service->id)
            ->whereDate('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time_start')
            ->get()
            ->map(fn($slot) => [
                'id' => $slot->id,
                'date' => $slot->date->format('d M Y'),
                'time' => $slot->time_start . ' - ' . $slot->time_end,
                'is_current' => $slot->id == $this->currentSlot?->id,
                'is_booked' => $slot->orders()
                    ->where('status', '!=', $this->order->status)
                    ->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])
                    ->count() >= $slot->max_bookings,
            ])
            ->values();
    }

    public function updateSlot()
    {
        $this->validate([
            'newSlotId' => 'required|exists:service_time_slots,id',
        ]);

        if ($this->newSlotId == $this->order->time_slot_id) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Slot yang dipilih sama dengan slot saat ini']);
            return;
        }

        $oldSlotId = $this->order->time_slot_id;
        $newSlot = ServiceTimeSlot::find($this->newSlotId);

        // Cek slot baru masih available
        $bookedCount = $newSlot->orders()
            ->whereIn('status', ['menunggu_konfirmasi', 'dikonfirmasi', 'dikerjakan', 'menunggu_persetujuan', 'selesai'])
            ->count();

        if ($bookedCount >= $newSlot->max_bookings) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Slot waktu sudah penuh']);
            return;
        }

        DB::transaction(function () use ($oldSlotId, $newSlot) {
            // Update order
            $this->order->update(['time_slot_id' => $newSlot->id]);

            // Insert history
            TimeSlotHistory::create([
                'order_id' => $this->order->id,
                'old_time_slot_id' => $oldSlotId,
                'new_time_slot_id' => $newSlot->id,
                'changed_by' => 'seller',
                'notes' => $this->notes ?? 'Seller mengubah jadwal booking',
            ]);
        });

        $this->loadAvailableSlots();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Jadwal booking berhasil diubah']);
        $this->dispatch('slotUpdated');
    }

    public function render()
    {
        return view('livewire.booking.order-time-slot-editor');
    }
}

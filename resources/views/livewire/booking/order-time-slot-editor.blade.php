<div class="space-y-6">
    {{-- Current Slot --}}
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
        <h4 class="font-bold text-gray-900 mb-2">Slot Saat Ini</h4>
        @if($currentSlot)
            <div class="flex items-center gap-3">
                <div class="flex-1">
                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($currentSlot->date)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $currentSlot->time_start }} - {{ $currentSlot->time_end }}</p>
                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal {{ $currentSlot->max_bookings }} booking • {{ $currentSlot->getAvailableCountAttribute() }} tersisa
                    </p>
                </div>
                <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-bold text-green-800">Active</span>
            </div>
        @else
            <p class="text-sm text-gray-500">Belum ada slot waktu yang dipilih.</p>
        @endif
    </div>

    {{-- Available Slots --}}
    <div>
        <h4 class="font-bold text-gray-900 mb-3">Ganti Jadwal</h4>
        <p class="mb-4 text-sm text-gray-500">Pilih slot baru dari yang tersedia:</p>
        
        <div class="space-y-3">
            @foreach($slots as $slot)
                <label class="relative flex cursor-pointer items-start gap-3 rounded-lg border {{ $slot['is_booked'] ? 'border-gray-200 bg-gray-100 opacity-50' : 'border-gray-200 bg-white hover:border-blue-300' }} p-3">
                    <input 
                        type="radio" 
                        wire:model.live="newSlotId"
                        value="{{ $slot['id'] }}"
                        {{ $slot['is_booked'] ? 'disabled' : '' }}
                        class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500">
                    
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-gray-900">{{ $slot['date'] }}</p>
                            @if($slot['is_current'])
                                <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-bold text-green-800">Current</span>
                            @endif
                            @if($slot['is_booked'])
                                <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-800">Full</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">{{ $slot['time'] }}</p>
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Notes --}}
    <div>
        <label class="mb-2 block text-sm font-bold text-gray-700">Catatan Perubahan</label>
        <textarea 
            wire:model.live="notes"
            rows="3"
            placeholder="Alasan perubahan jadwal (opsional)"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"></textarea>
    </div>

    {{-- Action Buttons --}}
    <div class="flex justify-end gap-3 pt-4">
        <button wire:click="updateSlot" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">
            Update Jadwal
        </button>
    </div>
</div>

@script
<script>
    $wire.on('alert', (event) => {
        const data = event[0] || event;
        alert(data.message);
    });

    $wire.on('slotUpdated', () => {
        console.log('Slot updated');
    });
</script>
@endscript

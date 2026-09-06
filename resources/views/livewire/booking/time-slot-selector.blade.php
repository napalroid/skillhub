<div class="space-y-4">
    {{-- Date Selector --}}
    <div class="mb-4">
        <label class="mb-2 block text-sm font-bold text-gray-700">Pilih Tanggal</label>
        <div class="flex flex-wrap gap-2">
            @foreach($availableDates as $date)
                <button 
                    type="button"
                    wire:click="changeDate('{{ $date }}')"
                    class="rounded-full px-3 py-1 text-xs font-bold transition {{ $date === $selectedDate ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ \Carbon\Carbon::parse($date)->format('d M') }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Time Slots Grid --}}
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @if(count($slots) === 0)
            <div class="col-span-full rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                <p class="text-sm text-gray-500">Tidak ada slot tersedia untuk tanggal ini.</p>
            </div>
        @else
            @foreach($slots as $slot)
                <button 
                    type="button"
                    wire:click="selectSlot({{ $slot->id }})"
                    class="relative flex flex-col items-start rounded-lg border {{ $slot->id == $selectedSlotId ? 'border-blue-600 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-400' }} p-4 text-left transition shadow-sm">
                    <span class="font-bold text-gray-900">{{ $slot->time_start }} - {{ $slot->time_end }}</span>
                    <span class="mt-1 text-xs text-gray-500">
                        {{ $slot->getAvailableCountAttribute() }} slot tersisa
                    </span>
                </button>
            @endforeach
        @endif
    </div>

    <input type="hidden" wire:model="selectedSlotId">
</div>

@script
<script>
    $wire.on('slotSelected', (event) => {
        console.log('Slot selected:', event.detail.slotId);
    });
</script>
@endscript

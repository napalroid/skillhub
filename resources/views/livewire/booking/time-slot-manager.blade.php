<div class="space-y-6">
    {{-- Header --}}
    <div class="border-b border-gray-200 pb-4">
        <h3 class="font-bold text-lg text-gray-900">Manajemen Jam Tersedia</h3>
        <p class="mt-1 text-sm text-gray-500">Kelola slot waktu yang tersedia untuk jasa ini. Buyer akan memilih jam saat booking.</p>
    </div>

    {{-- Date Selector --}}    
    <div class="flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Pilih Tanggal:</label>
        <input type="date" wire:model.live="selectedDate" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
    </div>

    {{-- Slot List for Selected Date --}}
    <div class="border-t border-gray-100 pt-6">
        <div class="flex items-center justify-between">
            <h4 class="font-bold text-gray-900">Slot pada {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</h4>
            <span class="text-xs text-gray-500">{{ count($timeSlots) }} slot tersedia</span>
        </div>

        <div class="mt-3 space-y-2">
            @if(count($timeSlots) === 0)
                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada slot untuk tanggal ini. Tambahkan slot baru di bawah.</p>
                </div>
            @else
                @foreach($timeSlots as $slot)
                    <div class="flex items-start justify-between rounded-lg border border-gray-200 bg-white p-3 shadow-sm gap-3">
                        <div class="flex-1">
                            <p class="font-bold text-gray-900">{{ $slot->time_start }} - {{ $slot->time_end }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Kapasitas: {{ $slot->max_bookings }} booking
                            </p>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <span class="inline-flex items-center gap-1 rounded bg-blue-100 px-2 py-1 text-blue-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Booked: {{ $slot->orders()->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])->count() }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded {{ $slot->getAvailableCountAttribute() > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Tersisa: {{ $slot->getAvailableCountAttribute() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="removeSlot({{ $slot->id }})" 
                                    class="rounded bg-red-100 px-3 py-1 text-xs font-bold text-red-600 hover:bg-red-200"
                                    title="Hapus slot">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Add New Slot Form --}}
    <div class="border-t border-gray-100 pt-6">
        <h4 class="font-bold text-gray-900 mb-3">Tambah Slot Baru</h4>
        
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Tanggal</label>
                <input type="date" wire:model.live="newSlot.date" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Mulai</label>
                <input type="time" wire:model.live="newSlot.time_start" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Selesai</label>
                <input type="time" wire:model.live="newSlot.time_end" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Maks Booking</label>
                <input type="number" wire:model.live="newSlot.max_bookings" min="1" max="100" value="1" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div class="mt-4">
            <button wire:click="addSlot" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-bold text-white hover:bg-blue-700">
                + Tambah Slot
            </button>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
        <h4 class="font-bold text-blue-900 mb-2">Ringkasan Slot</h4>
        <div class="grid gap-2 sm:grid-cols-3">
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $service->timeSlots()->whereDate('date', '>=', now()->format('Y-m-d'))->count() }}</p>
                <p class="text-xs text-blue-600">Total Slot</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $service->timeSlots()->whereDate('date', '>=', now()->format('Y-m-d'))->sum('max_bookings') }}</p>
                <p class="text-xs text-blue-600">Kuota Total</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-blue-700">{{ $service->timeSlots()->whereDate('date', '>=', now()->format('Y-m-d'))->get()->sum(fn($s) => $s->getAvailableCountAttribute()) }}</p>
                <p class="text-xs text-blue-600">Tersedia</p>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('alert', (event) => {
        const data = event[0] || event;
        alert(data.message);
    });
</script>
@endscript

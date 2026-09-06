<x-layouts.app title="Buat Pesanan — {{ $service->title }}">
    @vite(['resources/js/booking-calendar.jsx'])

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:py-14">

        <div class="mb-8">
            <a href="{{ route('services.show', $service) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400 transition hover:text-gray-700">
                <span aria-hidden="true">←</span> Kembali ke jasa
            </a>
            <div class="mt-6 flex items-center justify-between">
                <div>
                    @if($service->time_slots_enabled)
                        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Pilih tanggal dan waktu</h1>
                        <p class="mt-2 text-sm text-gray-400">Langkah 1 dari 3</p>
                    @else
                        <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Buat Pesanan</h1>
                        <p class="mt-2 text-sm text-gray-400">Isi informasi booking untuk pesanan Anda</p>
                    @endif
                </div>
            </div>
            @if($service->time_slots_enabled)
            <div class="mt-4 h-1 w-full overflow-hidden rounded-full bg-gray-800">
                <div class="h-full w-1/3 bg-white"></div>
            </div>
            @endif
        </div>

        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <input type="hidden" name="time_slot_id" id="selected_slot_id">

            @if($service->time_slots_enabled)
            <div id="booking-calendar-root" 
                 data-service-id="{{ $service->id }}"
                 data-available-slots="{{ json_encode($availableSlots) }}">
            </div>
            @endif

            @if($service->booking_config && isset($service->booking_config['enabled']) && $service->booking_config['enabled'] && !empty($service->booking_config['fields']))
            <div class="mt-8 rounded-xl border border-gray-800 bg-black p-6">
                <h3 class="mb-6 text-lg font-bold text-white">Informasi Booking</h3>
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach($service->booking_config['fields'] as $field)
                    <div>
                        <label for="booking_{{ $field['name'] }}" class="mb-2 block text-sm font-bold text-white">
                            {{ $field['label'] }}
                            @if($field['required']) <span class="text-red-400">*</span> @endif
                        </label>
                        
                        @if($field['type'] === 'textarea')
                            <textarea 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                rows="4"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full resize-y rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">{{ old('booking_data.' . $field['name']) }}</textarea>
                        
                        @elseif($field['type'] === 'select')
                            <select 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                                <option value="">Pilih {{ $field['label'] }}</option>
                                @foreach($field['options'] ?? [] as $option)
                                    <option value="{{ $option }}" {{ old('booking_data.' . $field['name']) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        
                        @elseif($field['type'] === 'number')
                            <input 
                                type="number" 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                value="{{ old('booking_data.' . $field['name']) }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                min="{{ $field['min'] ?? '' }}"
                                max="{{ $field['max'] ?? '' }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        @elseif($field['type'] === 'date')
                            <input 
                                type="date" 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                value="{{ old('booking_data.' . $field['name']) }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        @elseif($field['type'] === 'time')
                            <input 
                                type="time" 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                value="{{ old('booking_data.' . $field['name']) }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        
                        @else
                            <input 
                                type="text" 
                                name="booking_data[{{ $field['name'] }}]" 
                                id="booking_{{ $field['name'] }}"
                                value="{{ old('booking_data.' . $field['name']) }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                maxlength="{{ $field['maxlength'] ?? 255 }}"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">
                        @endif

                        @if(!empty($field['help_text']))
                            <p class="mt-1 text-xs text-gray-400">{{ $field['help_text'] }}</p>
                        @endif
                        
                        @error('booking_data.' . $field['name'])
                            <p class="mt-2 text-xs font-medium text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="mt-8 rounded-xl border border-gray-800 bg-black p-6">
                <label for="message" class="mb-2 block text-sm font-bold text-white">Pesan untuk penyedia jasa</label>
                <textarea name="message" id="message" rows="4" maxlength="1000"
                          placeholder="Halo, saya tertarik dengan jasa ini..."
                          class="w-full resize-y rounded-lg border border-gray-700 bg-gray-900 px-4 py-3 text-sm text-white outline-none transition placeholder:text-gray-500 focus:border-gray-500 focus:ring-1 focus:ring-gray-500">{{ old('message') }}</textarea>
                <p class="mt-2 text-xs text-gray-400">Opsional. Bisa langsung bayar, atau diskusikan dulu sebelum menyetujui harga.</p>
                @error('message')<p class="mt-2 text-xs font-medium text-red-400">{{ $message }}</p>@enderror
            </div>

            <div class="fixed bottom-0 left-0 right-0 z-50 border-t border-gray-800 bg-black/95 backdrop-blur-sm">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
                    <div class="flex flex-col gap-1">
                        <p class="text-xs text-gray-400">Total Harga</p>
                        <p class="text-lg font-bold text-white">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('services.show', $service) }}"
                           class="rounded-lg border border-gray-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-gray-900">
                            Kembali
                        </a>
                        @if($service->time_slots_enabled)
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-700 px-6 py-3 text-sm font-bold text-gray-400 transition hover:bg-gray-600 cursor-not-allowed">
                            <span>Pilih Waktu Dulu</span>
                            <span aria-hidden="true">←</span>
                        </button>
                        @else
                        <button type="submit" id="submitBtn"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-100">
                            <span>Lanjut Pembayaran</span>
                            <span aria-hidden="true">→</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="h-24"></div>
        </form>
    </div>

    <style>
        body {
            background: #000;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timeSlotEnabled = {{ $service->time_slots_enabled ? 'true' : 'false' }};
            
            @if($service->time_slots_enabled)
            const submitBtn = document.getElementById('submitBtn');
            const slotInput = document.getElementById('selected_slot_id');
            
            window.updateSubmitButton = function(slotId) {
                if (slotId) {
                    submitBtn.disabled = false;
                    submitBtn.className = 'inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-100';
                    submitBtn.innerHTML = '<span>Lanjut Pembayaran</span><span aria-hidden="true">→</span>';
                } else {
                    submitBtn.disabled = true;
                    submitBtn.className = 'inline-flex items-center justify-center gap-2 rounded-lg bg-gray-700 px-6 py-3 text-sm font-bold text-gray-400 transition hover:bg-gray-600 cursor-not-allowed';
                    submitBtn.innerHTML = '<span>Pilih Waktu Dulu</span><span aria-hidden="true">←</span>';
                }
            };
            
            slotInput.addEventListener('change', function() {
                window.updateSubmitButton(this.value);
            });
            @endif
        });
    </script>
</x-layouts.app>

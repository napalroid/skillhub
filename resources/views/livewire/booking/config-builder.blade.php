<div class="space-y-6">
    {{-- Time Slots Toggle - MOVED TO TOP --}}
    <div class="rounded-lg border-2 border-blue-200 bg-blue-50 p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-base font-bold text-slate-900">Time Slots / Jadwal Booking</p>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Aktifkan jika jasa ini membutuhkan pemilihan hari & jam spesifik.<br>
                    <span class="text-slate-500">Nonaktifkan untuk: joki ML, desain grafis, jasa online tanpa jadwal tetap.</span>
                </p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <button type="button" 
                        wire:click="toggleTimeSlots" 
                        class="relative inline-flex h-8 w-14 items-center rounded-full transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm {{ $service->time_slots_enabled ? 'bg-green-500' : 'bg-gray-400' }}">
                    <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow transition-transform {{ $service->time_slots_enabled ? 'translate-x-7' : 'translate-x-1' }}"></span>
                </button>
                <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded {{ $service->time_slots_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $service->time_slots_enabled ? 'AKTIF' : 'NONAKTIF' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <div class="border-b border-gray-200 pb-4">
        <h3 class="text-xl font-bold text-gray-900">Konfigurasi Field Booking</h3>
        <p class="mt-2 text-sm text-gray-500">Atur field yang harus diisi buyer saat memesan jasa ini. Maksimal 15 field.</p>
    </div>

    {{-- Template Suggestions --}}
    @if(count($templates) > 0)
    <div class="rounded border border-gray-200 bg-gray-50 p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-gray-900">Gunakan Template</p>
                <p class="mt-1 text-xs text-gray-500">Pilih template sesuai jenis jasa untuk mempercepat konfigurasi</p>
            </div>
            <button type="button" wire:click="$toggle('showTemplateModal')" class="shrink-0 rounded border border-[#0051BA] bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-[#0051BA] transition hover:bg-[#0051BA] hover:text-white">
                Pilih Template
            </button>
        </div>
    </div>
    @endif

    {{-- Current Fields List --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <p class="text-sm font-bold text-gray-900">Field Saat Ini ({{ count($fields) }}/15)</p>
            @if(count($fields) > 0)
            <button type="button" wire:click="clearConfig" wire:confirm="Yakin ingin menghapus semua field?" class="text-xs text-red-600 hover:underline">
                Hapus Semua
            </button>
            @endif
        </div>

        @if(count($fields) === 0)
        <div class="rounded border border-dashed border-gray-300 bg-gray-50 p-8 text-center">
            <p class="text-sm text-gray-500">Belum ada field. Tambahkan field atau gunakan template.</p>
        </div>
        @else
        <div class="space-y-2">
        @foreach($fields as $index => $field)
        <div wire:key="field-{{ $index }}" class="flex items-start gap-3 rounded border border-gray-200 bg-white p-4">
            <div class="flex-1">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900">{{ $field['label'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">
                            <span class="font-mono text-gray-700">{{ $field['name'] }}</span> • 
                            <span>{{ config('booking.field_types')[$field['type']] ?? $field['type'] }}</span> • 
                            <span class="font-bold {{ $field['required'] ? 'text-red-600' : 'text-green-600' }}">
                                {{ $field['required'] ? 'Wajib' : 'Opsional' }}
                            </span>
                        </p>
                        @if(!empty($field['options']))
                        <p class="mt-2 text-xs text-gray-500">
                            Opsi: {{ implode(', ', array_slice($field['options'], 0, 3)) }}{{ count($field['options']) > 3 ? '...' : '' }}
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-1">
                        @if($index > 0)
                        <button type="button" wire:click="moveField({{ $index }}, 'up')" class="rounded p-1 text-gray-500 hover:bg-gray-100" title="Naik">↑</button>
                        @endif
                        @if($index < count($fields) - 1)
                        <button type="button" wire:click="moveField({{ $index }}, 'down')" class="rounded p-1 text-gray-500 hover:bg-gray-100" title="Turun">↓</button>
                        @endif
                        <button type="button" wire:click="removeField({{ $index }})" wire:loading.attr="disabled" class="rounded p-1 text-red-600 hover:bg-red-50" title="Hapus">×</button>
                    </div>
                </div>
            </div>
        </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Add New Field Form --}}
    <div class="rounded border border-gray-200 bg-gray-50 p-4">
        <p class="mb-3 text-sm font-bold text-gray-900">Tambah Field Baru</p>
        
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-900 mb-2">Label (Tampil ke User)</label>
                <input type="text" wire:model="newField.label" placeholder="Jenis Potongan Rambut" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#0051BA] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-900 mb-2">Tipe Field</label>
                <select wire:model="newField.type" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#0051BA] focus:outline-none">
                    @foreach(config('booking.field_types', []) as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-900 mb-2">Wajib Diisi?</label>
                <select wire:model="newField.required" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#0051BA] focus:outline-none">
                    <option value="0">Opsional</option>
                    <option value="1">Wajib</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-900 mb-2">Placeholder (opsional)</label>
                <input type="text" wire:model="newField.placeholder" placeholder="Teks bantuan untuk user" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#0051BA] focus:outline-none">
            </div>

            @if(in_array($newField['type'], ['select', 'radio', 'checkbox']))
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-900 mb-2">Opsi (pisahkan dengan koma)</label>
                <input type="text" wire:model="newField.options" placeholder="Opsi 1, Opsi 2, Opsi 3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:border-[#0051BA] focus:outline-none">
                <p class="mt-1 text-xs text-gray-500">Contoh: Crewcut, Fade, Undercut</p>
            </div>
            @endif
        </div>

        <div class="mt-4 flex justify-end">
            <button type="button" wire:click="addField" class="rounded bg-[#0051BA] px-4 py-2 text-xs font-bold uppercase tracking-wide text-white transition hover:bg-black">
                + Tambah Field
            </button>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end gap-3 border-t border-gray-200 pt-4">
        <a href="{{ route('services.my') }}" class="rounded border border-gray-300 px-4 py-2 text-sm font-bold text-gray-700 transition hover:bg-gray-50">
            Batal
        </a>
        <button type="button" wire:click="saveConfig" class="rounded bg-[#0051BA] px-6 py-2 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-black">
            Simpan Konfigurasi
        </button>
    </div>

    {{-- Template Modal --}}
    @if($showTemplateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-2xl rounded-lg bg-white shadow-2xl">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Pilih Template</h3>
                    <button type="button" wire:click="$toggle('showTemplateModal')" class="text-2xl leading-none text-gray-500 hover:text-gray-900">&times;</button>
                </div>
            </div>
            <div class="max-h-96 overflow-y-auto p-6">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($templates as $key => $template)
                    <button type="button" wire:click="applyTemplate('{{ $key }}')" class="rounded-lg border border-gray-200 p-4 text-left transition hover:border-[#0051BA] hover:bg-[#0051BA]/5">
                        <p class="text-sm font-bold text-gray-900">{{ $template['name'] }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ count($template['fields']) }} field</p>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
    $wire.on('alert', (event) => {
        const data = event[0] || event;
        alert(data.message);
    });
</script>
@endscript

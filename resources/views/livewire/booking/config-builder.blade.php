<div class="space-y-6">
    {{-- Header --}}
    <div class="border-b border-border pb-4">
        <h3 class="font-heading text-xl font-bold text-black">Konfigurasi Field Booking</h3>
        <p class="mt-2 text-sm text-text-secondary">Atur field yang harus diisi buyer saat memesan jasa ini. Maksimal 15 field.</p>
    </div>

    {{-- Template Suggestions --}}
    @if(count($templates) > 0)
    <div class="rounded border border-border bg-bg-soft p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-black">Gunakan Template</p>
                <p class="mt-1 text-xs text-text-secondary">Pilih template sesuai jenis jasa untuk mempercepat konfigurasi</p>
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
            <p class="text-sm font-bold text-black">Field Saat Ini ({{ count($fields) }}/15)</p>
            @if(count($fields) > 0)
            <button type="button" wire:click="clearConfig" wire:confirm="Yakin ingin menghapus semua field?" class="text-xs text-red-600 hover:underline">
                Hapus Semua
            </button>
            @endif
        </div>

        @if(count($fields) === 0)
        <div class="rounded border border-dashed border-border bg-bg-muted p-8 text-center">
            <p class="text-sm text-text-secondary">Belum ada field. Tambahkan field atau gunakan template.</p>
        </div>
        @else
        <div class="space-y-2">
            @foreach($fields as $index => $field)
            <div wire:key="field-{{ $index }}" class="flex items-start gap-3 rounded border border-border bg-white p-4">
                <div class="flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <p class="font-heading text-sm font-bold text-black">{{ $field['label'] }}</p>
                            <p class="mt-1 text-xs text-text-secondary">
                                <span class="font-mono">{{ $field['name'] }}</span> • 
                                <span>{{ config('booking.field_types')[$field['type']] ?? $field['type'] }}</span> • 
                                <span class="font-bold {{ $field['required'] ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $field['required'] ? 'Wajib' : 'Opsional' }}
                                </span>
                            </p>
                            @if(!empty($field['options']))
                            <p class="mt-2 text-xs text-text-secondary">
                                Opsi: {{ implode(', ', array_slice($field['options'], 0, 3)) }}{{ count($field['options']) > 3 ? '...' : '' }}
                            </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-1">
                            @if($index > 0)
                            <button type="button" wire:click="moveField({{ $index }}, 'up')" class="rounded p-1 text-text-secondary hover:bg-bg-soft" title="Naik">↑</button>
                            @endif
                            @if($index < count($fields) - 1)
                            <button type="button" wire:click="moveField({{ $index }}, 'down')" class="rounded p-1 text-text-secondary hover:bg-bg-soft" title="Turun">↓</button>
                            @endif
                            <button type="button" wire:click="removeField({{ $index }})" class="rounded p-1 text-red-600 hover:bg-red-50" title="Hapus">×</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Add New Field Form --}}
    <div class="rounded border border-border bg-bg-soft p-4">
        <p class="mb-3 text-sm font-bold text-black">Tambah Field Baru</p>
        
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Label (Tampil ke User)</label>
                <input type="text" wire:model="newField.label" placeholder="Jenis Potongan Rambut" class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Tipe Field</label>
                <select wire:model="newField.type" class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none">
                    @foreach(config('booking.field_types', []) as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Wajib Diisi?</label>
                <select wire:model="newField.required" class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none">
                    <option value="0">Opsional</option>
                    <option value="1">Wajib</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Placeholder (opsional)</label>
                <input type="text" wire:model="newField.placeholder" placeholder="Teks bantuan untuk user" class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none">
            </div>

            @if(in_array($newField['type'], ['select', 'radio', 'checkbox']))
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Opsi (pisahkan dengan koma)</label>
                <input type="text" wire:model="newField.options" placeholder="Opsi 1, Opsi 2, Opsi 3" class="w-full px-3 py-2 border border-border rounded text-sm focus:border-[#0051BA] focus:outline-none">
                <p class="mt-1 text-xs text-text-secondary">Contoh: Crewcut, Fade, Undercut</p>
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
    <div class="flex justify-end gap-3 border-t border-border pt-4">
        <a href="{{ route('services.my') }}" class="rounded border border-border px-4 py-2 text-sm font-bold text-black transition hover:bg-bg-soft">
            Batal
        </a>
        <button type="button" wire:click="saveConfig" class="rounded bg-[#0051BA] px-6 py-2 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-black">
            Simpan Konfigurasi
        </button>
    </div>

    {{-- Template Modal --}}
    @if($showTemplateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-2xl rounded bg-white shadow-xl">
            <div class="border-b border-border px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading text-lg font-bold text-black">Pilih Template</h3>
                    <button type="button" wire:click="$toggle('showTemplateModal')" class="text-2xl leading-none text-text-secondary hover:text-black">&times;</button>
                </div>
            </div>
            <div class="max-h-96 overflow-y-auto p-6">
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($templates as $key => $template)
                    <button type="button" wire:click="applyTemplate('{{ $key }}')" class="rounded border border-border p-4 text-left transition hover:border-[#0051BA] hover:bg-[#0051BA]/5">
                        <p class="font-heading text-sm font-bold text-black">{{ $template['name'] }}</p>
                        <p class="mt-1 text-xs text-text-secondary">{{ count($template['fields']) }} field</p>
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

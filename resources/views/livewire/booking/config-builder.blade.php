<div class="space-y-10 px-6 lg:px-8 py-8 lg:py-10">
    
    <div class="border-b border-black/10 pb-10 mb-2">
        <div class="flex items-start justify-between gap-8">
            <div class="flex-1">
                <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-4">Time Slots</div>
                <p class="text-sm text-black/60 leading-relaxed max-w-lg">
                    Aktifkan jika jasa ini membutuhkan pemilihan hari dan jam spesifik. 
                    <span class="text-black/40">Nonaktifkan untuk joki ML, desain grafis, jasa online tanpa jadwal tetap.</span>
                </p>
            </div>
            <div class="flex items-center gap-4 shrink-0">
                <select wire:model.live="timeSlotsEnabled" 
                        class="px-3 py-2 border-2 border-black rounded text-sm font-medium text-black bg-white hover:bg-black/5 transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                    <option value="0" {{ $timeSlotsEnabled == 0 ? 'selected' : '' }}>Nonaktif</option>
                    <option value="1" {{ $timeSlotsEnabled == 1 ? 'selected' : '' }}>Aktif</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_16rem] border-b border-black/10 pb-10">
        <div>
            <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">Informasi buyer</div>
            <p class="text-sm leading-relaxed text-black/60">Tambahkan field untuk informasi yang perlu dikumpulkan sebelum buyer melanjutkan booking. Field wajib akan divalidasi oleh alur booking yang sudah ada.</p>
        </div>
        <aside class="border border-black/10 bg-[#fafafa] p-4">
            <div class="text-[10px] font-medium tracking-[0.1em] uppercase text-black/40 mb-3">Preview buyer</div>
            @forelse($fields as $field)
                <div class="mb-3 last:mb-0">
                    <div class="text-[11px] font-medium text-black">{{ $field['label'] }}@if($field['required']) <span class="text-[#155eef]">*</span>@endif</div>
                    @if(in_array($field['type'], ['select', 'radio']))
                        <div class="mt-1 border-b border-black/15 py-1.5 text-[11px] text-black/35">Pilih {{ $field['label'] }}</div>
                    @elseif($field['type'] === 'textarea')
                        <div class="mt-1 h-9 border border-black/10 bg-white"></div>
                    @else
                        <div class="mt-1 border-b border-black/15 py-1.5 text-[11px] text-black/35">{{ $field['placeholder'] ?? 'Jawaban buyer' }}</div>
                    @endif
                </div>
            @empty
                <p class="text-xs leading-relaxed text-black/35">Preview akan tampil setelah field pertama ditambahkan.</p>
            @endforelse
        </aside>
    </div>

    @if(count($templates) > 0)
    <div class="border-b border-black/10 pb-10">
        <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-4">Template</div>
        <div class="flex items-center justify-between gap-4">
            <p class="text-sm text-black/60">Gunakan template untuk mempercepat konfigurasi.</p>
            <button type="button" 
                    wire:click="$toggle('showTemplateModal')" 
                    class="px-4 py-2 border border-black text-black text-xs font-medium hover:bg-black hover:text-white transition-all">
                Pilih Template
            </button>
        </div>
    </div>
    @endif

    <div>
        <div class="flex items-center justify-between gap-4 mb-6">
            <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-black/40">
                Field Saat Ini - {{ count($fields) }}/15
            </div>
            @if(count($fields) > 0)
            <button type="button" 
                    wire:click="clearConfig" 
                    wire:confirm="Yakin ingin menghapus semua field?" 
                    class="text-xs font-medium text-black hover:underline hover:text-black/70 transition-colors">
                Hapus Semua
            </button>
            @endif
        </div>

        @if(count($fields) === 0)
        <div class="py-20 border-t border-black/10">
            <p class="text-sm text-black/40 text-center">Belum ada field. Tambahkan field di bawah atau gunakan template.</p>
        </div>
        @else
        <div class="border-t border-black/10 divide-y divide-black/5">
        @foreach($fields as $index => $field)
        <div wire:key="field-{{ $index }}" 
             class="group py-6 flex items-start gap-6">
            
            <div class="text-xs font-medium tracking-[0.12em] text-black/20 w-6 shrink-0 pt-1">
                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
            </div>

            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-black mb-2">{{ $field['label'] }}</div>
                <div class="flex items-center gap-2 text-xs text-black/40">
                    <span class="font-mono">{{ $field['name'] }}</span>
                    <span class="w-0.5 h-0.5 rounded-full bg-black/20"></span>
                    <span>{{ config('booking.field_types')[$field['type']] ?? $field['type'] }}</span>
                    <span class="w-0.5 h-0.5 rounded-full bg-black/20"></span>
                    <span class="{{ $field['required'] ? 'text-black font-medium' : 'text-black/50' }}">
                        {{ $field['required'] ? 'Wajib' : 'Opsional' }}
                    </span>
                </div>
                @if(!empty($field['options']))
                <div class="mt-2 text-xs text-black/30">
                    Opsi: {{ implode(', ', array_slice($field['options'], 0, 3)) }}{{ count($field['options']) > 3 ? '...' : '' }}
                </div>
                @endif
            </div>

                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                @if($index > 0)
                                <button type="button" 
                                        wire:click="moveField({{ $index }}, 'up')" 
                                        class="p-2 text-black/30 hover:text-white hover:bg-black transition-all"
                                        aria-label="Move up">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                @endif
                                @if($index < count($fields) - 1)
                                <button type="button" 
                                        wire:click="moveField({{ $index }}, 'down')" 
                                        class="p-2 text-black/30 hover:text-white hover:bg-black transition-all"
                                        aria-label="Move down">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                @endif
                                <button type="button" 
                                        wire:click="removeField({{ $index }})" 
                                        wire:loading.attr="disabled" 
                                        class="p-2 text-black/30 hover:text-white hover:bg-black transition-all"
                                        aria-label="Remove field">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
        </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="border-t border-black/10 pt-10">
        <div class="text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-6">Tambah Field Baru</div>
        
        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">
                    Label
                </label>
                <input type="text" 
                       wire:model="newField.label" 
                       placeholder="Rank Sekarang"
                       class="w-full px-0 py-3 bg-transparent border-0 border-b border-black/15 text-sm text-black placeholder:text-black/30 focus:outline-none focus:border-black focus:ring-0 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">
                    Tipe Field
                </label>
                <select wire:model="newField.type" 
                        class="w-full px-0 py-3 bg-transparent border-0 border-b border-black/15 text-sm text-black focus:outline-none focus:border-black focus:ring-0 transition-colors cursor-pointer">
                    @foreach(config('booking.field_types', []) as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">
                    Status
                </label>
                <select wire:model="newField.required" 
                        class="w-full px-0 py-3 bg-transparent border-0 border-b border-black/15 text-sm text-black focus:outline-none focus:border-black focus:ring-0 transition-colors cursor-pointer">
                    <option value="0">Opsional</option>
                    <option value="1">Wajib</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">
                    Placeholder
                </label>
                <input type="text" 
                       wire:model="newField.placeholder" 
                       placeholder="Teks bantuan"
                       class="w-full px-0 py-3 bg-transparent border-0 border-b border-black/15 text-sm text-black placeholder:text-black/30 focus:outline-none focus:border-black focus:ring-0 transition-colors">
            </div>

            @if(in_array($newField['type'], ['select', 'radio', 'checkbox']))
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-medium tracking-[0.08em] uppercase text-black/40 mb-3">
                    Opsi
                </label>
                <input type="text" 
                       wire:model="newField.options" 
                       placeholder="Opsi 1, Opsi 2, Opsi 3"
                       class="w-full px-0 py-3 bg-transparent border-0 border-b border-black/15 text-sm text-black placeholder:text-black/30 focus:outline-none focus:border-black focus:ring-0 transition-colors">
                <p class="mt-2 text-xs text-black/30">Pisahkan dengan koma. Contoh: Warrior, Epic, Legend</p>
            </div>
            @endif
        </div>

        <div class="flex justify-end mt-8">
            <button type="button" 
                    wire:click="addField" 
                    class="px-5 py-2.5 bg-black text-white text-sm font-medium hover:bg-black/90 transition-all">
                Tambah Field
            </button>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 pt-8 border-t border-black/10">
        <a href="{{ route('services.my') }}" 
           class="px-6 py-3 text-sm font-medium text-black/50 hover:text-black hover:bg-black/5 transition-all">
            Batal
        </a>
        <button type="button" 
                wire:click="saveConfig" 
                class="px-6 py-3 bg-black text-white text-sm font-medium hover:bg-black/90 transition-all">
            Simpan Konfigurasi
        </button>
    </div>

    @if($showTemplateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" 
         role="dialog" 
         aria-modal="true" 
         aria-labelledby="template-modal-title">
        <div class="w-full max-w-2xl bg-white max-h-[90vh] flex flex-col">
            <div class="border-b border-black/10 px-6 py-5 flex items-center justify-between shrink-0">
                <div>
                    <div class="text-[10px] font-medium tracking-[0.12em] uppercase text-black/35 mb-1">Template</div>
                    <h3 id="template-modal-title" class="text-lg font-semibold text-black">Pilih Template</h3>
                </div>
                 <button type="button" 
                         wire:click="$toggle('showTemplateModal')" 
                         class="p-2 text-black/40 hover:text-white hover:bg-black transition-all"
                         aria-label="Close modal">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                     </svg>
                 </button>
            </div>
             <div class="overflow-y-auto p-6">
                 <style>
                     .template-btn:hover { background-color: #000 !important; border-color: #000 !important; }
                     .template-btn:hover div { color: #fff !important; }
                 </style>
                 <div class="grid gap-3 sm:grid-cols-2">
                     @foreach($templates as $key => $template)
                     <button type="button" 
                             wire:click="applyTemplate('{{ $key }}')" 
                             class="template-btn relative text-left p-5 border border-black/10 bg-white transition-all">
                         <div class="text-sm font-semibold text-black mb-1">{{ $template['name'] }}</div>
                         <div class="text-xs text-black/40">{{ count($template['fields']) }} field</div>
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

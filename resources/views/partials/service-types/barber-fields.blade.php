<div class="space-y-6" x-data="{
    availableHaircutTypes: [],
    additionalServices: [],
    
    addHaircutType(type) {
        if (type && !this.availableHaircutTypes.includes(type)) {
            this.availableHaircutTypes.push(type);
        }
    },
    
    removeHaircutType(index) {
        this.availableHaircutTypes.splice(index, 1);
    },
    
    addAdditionalService() {
        this.additionalServices.push({ name: '', price: '' });
    },
    
    removeAdditionalService(index) {
        this.additionalServices.splice(index, 1);
    }
}">
    {{-- Available Haircut Types --}}
    <div class="bg-bg-soft p-4 rounded-lg border border-border">
        <h4 class="font-heading text-sm font-bold uppercase tracking-wider text-black mb-3">Jenis Potongan yang Tersedia</h4>
        
        {{-- Predefined options --}}
        <div class="grid grid-cols-2 gap-2 mb-4">
            @foreach(['Crewcut', 'Fade', 'Undercut', 'Pompadour', 'Layered', 'Buzzcut'] as $type)
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" 
                       value="{{ $type }}" 
                       x-model="availableHaircutTypes"
                       name="available_haircut_types[]"
                       class="sr-only peer">
                <div class="h-4 w-4 border border-border flex items-center justify-center peer-checked:border-[#0051BA] peer-checked:bg-[#0051BA]">
                    <svg x-show="availableHaircutTypes.includes('{{ $type }}')" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-text">{{ $type }}</span>
            </label>
            @endforeach
        </div>
        
        {{-- Custom input --}}
        <div class="flex gap-2">
            <input type="text" 
                   x-model="newHaircutType" 
                   @keyup.enter="addHaircutType(newHaircutType); newHaircutType = ''" 
                   placeholder="Tambah jenis potongan lain"
                   class="flex-1 input-field text-sm">
            <button type="button" 
                    @click="addHaircutType(newHaircutType); newHaircutType = ''"
                    class="px-3 py-2 bg-[#0051BA] text-white text-sm font-bold uppercase tracking-wide hover:bg-[#003d8f] transition">
                +
            </button>
        </div>
        
        {{-- Selected types display --}}
        <div class="mt-4 flex flex-wrap gap-2" x-show="availableHaircutTypes.length > 0" x-cloak>
            <template x-for="(type, index) in availableHaircutTypes" :key="index">
                <span class="inline-flex items-center gap-1 bg-black/5 px-3 py-1.5 text-sm rounded-full">
                    <span x-text="type"></span>
                    <button type="button" 
                            @click="removeHaircutType(index)"
                            class="ml-1 text-xs text-text-secondary hover:text-black">
                        ×
                    </button>
                </span>
            </template>
        </div>
        
        @error('available_haircut_types')
        <p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>
        @enderror
        @error('available_haircut_types.*')
        <p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>
        @enderror
    </div>

    {{-- Estimated Duration --}}
    <div>
        <label for="estimated_duration_minutes" class="label-field">Estimasi Durasi per Sesi</label>
        <div class="relative">
            <input type="number" 
                   name="estimated_duration_minutes" 
                   id="estimated_duration_minutes" 
                   value="{{ old('estimated_duration_minutes', 30) }}" 
                   required 
                   min="15" 
                   max="180" 
                   class="input-field @error('estimated_duration_minutes') border-[#0051BA] @enderror">
            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center font-heading text-sm text-text-secondary">menit</span>
        </div>
        <p class="mt-2 text-xs text-text-secondary">Rata-rata waktu yang dibutuhkan untuk satu sesi potong rambut</p>
        @error('estimated_duration_minutes')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
    </div>

    {{-- Additional Services --}}
    <div class="bg-bg-soft p-4 rounded-lg border border-border">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-heading text-sm font-bold uppercase tracking-wider text-black">Layanan Tambahan (Opsional)</h4>
            <button type="button" 
                    @click="addAdditionalService"
                    class="text-xs font-bold uppercase tracking-wider text-[#0051BA] hover:text-black transition">
                + Tambah Layanan
            </button>
        </div>
        
        <div class="space-y-3" x-show="additionalServices.length > 0" x-cloak>
            <template x-for="(service, index) in additionalServices" :key="index">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <input type="text" 
                               x-model="service.name"
                               :name="'additional_services[' + index + '][name]'"
                               placeholder="Nama layanan"
                               class="w-full input-field text-sm">
                    </div>
                    <div class="flex gap-2">
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-text-secondary">Rp</span>
                            <input type="number" 
                                   x-model="service.price"
                                   :name="'additional_services[' + index + '][price]'"
                                   min="0"
                                   placeholder="Harga"
                                   class="w-full input-field text-sm pl-8">
                        </div>
                        <button type="button" 
                                @click="removeAdditionalService(index)"
                                class="px-2 py-1.5 border border-border hover:border-[#E4002B] text-[#E4002B] hover:text-white hover:bg-[#E4002B] transition">
                            ×
                        </button>
                    </div>
                </div>
            </template>
        </div>
        
        <p class="mt-2 text-xs text-text-secondary">Misal: Cuci rambut Rp 5.000, Hair spa Rp 15.000</p>
        @error('additional_services')
        <p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>
        @enderror
        @error('additional_services.*')
        <p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>
        @enderror
    </div>

    {{-- Seller Notes --}}
    <div>
        <label for="seller_notes" class="label-field">Catatan untuk Buyer</label>
        <textarea name="seller_notes" 
                  id="seller_notes" 
                  rows="3" 
                  placeholder="Misal: Alat yang digunakan, preferensi pelanggan, dll..."
                  class="input-field resize-y @error('seller_notes') border-[#0051BA] @enderror">{{ old('seller_notes') }}</textarea>
        @error('seller_notes')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
    </div>
</div>
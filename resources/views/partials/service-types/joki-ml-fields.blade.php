<div class="space-y-6">
    <input type="hidden" name="pricing_mode" value="auto">

    {{-- Fixed automatic pricing rule --}}
    <div class="bg-bg-soft p-4 rounded-lg border border-border">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-[#0051BA] text-xs font-bold text-white" aria-hidden="true">✓</span>
            <div>
                <h4 class="font-heading text-sm font-bold uppercase tracking-wider text-black">Harga Otomatis per Bintang</h4>
                <p class="mt-1 text-xs leading-5 text-text-secondary">Total pesanan buyer dihitung otomatis dari selisih rank dan bintang. Isi tarif untuk setiap rank di bawah ini.</p>
            </div>
        </div>
    </div>

    {{-- Price Per Star Configuration --}}
    <div class="bg-bg-soft p-4 rounded-lg border border-border">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-heading text-sm font-bold uppercase tracking-wider text-black">Harga per Bintang</h4>
            <div class="text-xs text-text-secondary">Isi harga untuk setiap rank</div>
        </div>
        
        <div class="grid gap-4">
            @foreach(['warrior' => 'Warrior', 'elite' => 'Elite', 'master' => 'Master', 'grandmaster' => 'Grandmaster', 'epic' => 'Epic', 'legend' => 'Legend', 'mythic' => 'Mythic', 'mythical_honor' => 'Mythical Honor', 'mythical_glory' => 'Mythical Glory', 'mythical_immortal' => 'Mythical Immortal'] as $key => $label)
            <div class="grid grid-cols-2 gap-4 items-end">
                <label class="font-heading text-sm font-bold text-black">{{ $label }}</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center font-heading text-xs font-bold text-text-secondary">Rp</span>
                    <input type="number" 
                           name="price_per_star[{{ $key }}]" 
                           value="{{ old('price_per_star.' . $key) }}"
                           required
                           min="1000"
                           placeholder="10000"
                           class="w-full input-field pl-12 tabular-nums @error('price_per_star.'.$key) border-[#0051BA] @enderror">
                </div>
                @error('price_per_star.'.$key)
                <p class="mt-1 text-xs font-medium text-[#0051BA] col-span-2">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>
    </div>

    {{-- Display Price (Mulai dari) --}}
    <div>
        <label for="display_price" class="label-field">Harga Tampilan <span class="font-medium normal-case tracking-normal text-text-secondary">(harga mulai dari)</span></label>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center font-heading text-sm font-bold text-text-secondary">Rp</span>
            <input type="number" 
                   name="display_price" 
                   id="display_price" 
                   value="{{ old('display_price') }}" 
                   required 
                   min="1000" 
                   inputmode="numeric" 
                   placeholder="50000" 
                   class="input-field pl-12 tabular-nums @error('display_price') border-[#0051BA] @enderror">
        </div>
        <p class="mt-2 text-xs text-text-secondary">Harga ini akan ditampilkan sebagai "Mulai dari Rp X" di marketplace</p>
        @error('display_price')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
    </div>

    {{-- Additional Configuration --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="estimated_completion_days" class="label-field">Estimasi Selesai (hari)</label>
            <input type="number" 
                   name="estimated_completion_days" 
                   id="estimated_completion_days" 
                   value="{{ old('estimated_completion_days', 3) }}" 
                   min="1" 
                   max="30" 
                   class="input-field @error('estimated_completion_days') border-[#0051BA] @enderror">
            @error('estimated_completion_days')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Special Notes --}}
    <div>
        <label for="special_notes" class="label-field">Catatan Khusus untuk Buyer</label>
        <textarea name="special_notes" 
                  id="special_notes" 
                  rows="3" 
                  placeholder="Misal: Hanya melayani rank tertentu, jam main, dll..."
                  class="input-field resize-y @error('special_notes') border-[#0051BA] @enderror">{{ old('special_notes') }}</textarea>
        @error('special_notes')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
    </div>
</div>

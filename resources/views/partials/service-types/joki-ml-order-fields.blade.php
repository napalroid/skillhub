@php
    $selectedCurrentRank = old('current_rank', 'master');
    $selectedTargetRank = old('target_rank', 'legend');
    $selectedCurrentConfig = $rankConfigs[$selectedCurrentRank] ?? reset($rankConfigs);
    $selectedTargetConfig = $rankConfigs[$selectedTargetRank] ?? reset($rankConfigs);
@endphp

<section class="rounded-lg border border-[#B9D6FF] bg-[#F5F9FF] p-4" aria-labelledby="automatic-price-title">
    <div class="flex items-start gap-3">
        <span class="flex h-6 w-6 flex-none items-center justify-center rounded-full bg-[#0051BA] text-xs font-black text-white" aria-hidden="true">✓</span>
        <div>
            <h2 id="automatic-price-title" class="font-heading text-sm font-bold text-black">Harga otomatis per bintang</h2>
            <p class="mt-1 text-xs leading-5 text-text-secondary">Total dihitung dari selisih rank dan bintang yang kamu pilih. Harga final akan tampil sebelum pembayaran tanpa menunggu persetujuan seller.</p>
        </div>
    </div>
</section>

<section class="rounded-lg border border-border bg-bg-soft p-6" aria-labelledby="rank-order-title" data-joki-rank-form data-ranks='@json($rankConfigs)'>
    <h2 id="rank-order-title" class="font-heading text-lg font-bold text-black">Detail rank akun</h2>
    <p class="mt-1 text-sm text-text-secondary">Pilih rank saat ini dan tujuan untuk menghitung harga otomatis.</p>

    <div class="mt-5 grid gap-5 lg:grid-cols-2">
        @foreach ([
            ['prefix' => 'current', 'title' => 'Rank saat ini', 'rank' => $selectedCurrentRank, 'config' => $selectedCurrentConfig],
            ['prefix' => 'target', 'title' => 'Rank tujuan', 'rank' => $selectedTargetRank, 'config' => $selectedTargetConfig],
        ] as $selection)
            <fieldset class="rounded-md border border-border bg-white p-4">
                <legend class="px-1 font-heading text-sm font-bold text-black">{{ $selection['title'] }}</legend>
                <div class="mt-2 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="label-field" for="{{ $selection['prefix'] }}_rank">Rank</label>
                        <select id="{{ $selection['prefix'] }}_rank" name="{{ $selection['prefix'] }}_rank" class="input-field w-full" data-rank-select required>
                            @foreach($rankConfigs as $rankKey => $rankConfig)
                                <option value="{{ $rankKey }}" @selected(old($selection['prefix'] . '_rank', $selection['rank']) === $rankKey)>{{ $rankConfig['label'] }}</option>
                            @endforeach
                        </select>
                        @error($selection['prefix'] . '_rank')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label-field" for="{{ $selection['prefix'] }}_division">Divisi</label>
                        <select id="{{ $selection['prefix'] }}_division" name="{{ $selection['prefix'] }}_division" class="input-field w-full" data-division-select required>
                            @for($division = 1; $division <= $selection['config']['divisions']; $division++)
                                <option value="{{ $division }}" @selected((int) old($selection['prefix'] . '_division', 1) === $division)>{{ $selection['config']['divisions'] === 1 ? 'Tidak ada divisi' : ['I', 'II', 'III', 'IV', 'V'][$selection['config']['divisions'] - $division] }}</option>
                            @endfor
                        </select>
                        @error($selection['prefix'] . '_division')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label-field" for="{{ $selection['prefix'] }}_stars">Bintang</label>
                        @php
                            $minStars = $selection['config']['divisions'] === 1 ? \App\Services\MlRankCalculator::getRelativeMinStars($selection['rank']) : 0;
                            $maxStars = $selection['config']['divisions'] === 1 ? \App\Services\MlRankCalculator::getRelativeMaxStars($selection['rank']) : $selection['config']['stars_per_division'] - 1;
                        @endphp
                        <input id="{{ $selection['prefix'] }}_stars" name="{{ $selection['prefix'] }}_stars" type="number" inputmode="numeric" value="{{ old($selection['prefix'] . '_stars', $minStars) }}" min="{{ $minStars }}" max="{{ $maxStars }}" class="input-field w-full" data-stars-input required>
                        @error($selection['prefix'] . '_stars')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>
        @endforeach
    </div>
</section>

<section class="rounded-lg border border-border bg-bg-soft p-6" aria-labelledby="account-order-title">
    <h2 id="account-order-title" class="font-heading text-lg font-bold text-black">Detail akun ML</h2>
    <p class="mt-1 text-sm text-text-secondary">Data ini digunakan seller hanya untuk mengerjakan pesananmu.</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
        <div>
            <label class="label-field" for="ml_username">Username ML</label>
            <input id="ml_username" name="ml_username" type="text" value="{{ old('ml_username') }}" maxlength="100" required autocomplete="username" class="input-field w-full @error('ml_username') border-[#0051BA] @enderror" placeholder="Masukkan username ML kamu">
            @error('ml_username')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label-field" for="ml_password">Password akun</label>
            <input id="ml_password" name="ml_password" type="password" minlength="6" required autocomplete="current-password" class="input-field w-full @error('ml_password') border-[#0051BA] @enderror" placeholder="Password akun ML">
            <p class="mt-2 text-xs text-text-secondary">Password dienkripsi sebelum disimpan.</p>
            @error('ml_password')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label-field" for="hero_notes">Catatan hero <span class="normal-case font-medium text-text-secondary">(opsional)</span></label>
            <textarea id="hero_notes" name="hero_notes" rows="3" maxlength="500" class="input-field w-full resize-y" placeholder="Contoh: Suka memakai Lancelot, jangan pakai Layla.">{{ old('hero_notes') }}</textarea>
        </div>
        <div>
            <label class="label-field" for="additional_notes">Catatan tambahan <span class="normal-case font-medium text-text-secondary">(opsional)</span></label>
            <textarea id="additional_notes" name="additional_notes" rows="3" maxlength="500" class="input-field w-full resize-y" placeholder="Contoh: Jam main dan preferensi lain.">{{ old('additional_notes') }}</textarea>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-joki-rank-form]');
    if (!form) return;

    const ranks = JSON.parse(form.dataset.ranks || '{}');
    const roman = ['I', 'II', 'III', 'IV', 'V'];
    const starRange = (rank, config) => {
        if (config.divisions !== 1) return [0, config.stars_per_division - 1];
        const ranges = { mythic: [0, 24], mythical_honor: [25, 49], mythical_glory: [50, 99], mythical_immortal: [100, 5863] };
        return ranges[rank] || [0, 0];
    };
    const refresh = (prefix) => {
        const rankSelect = form.querySelector(`#${prefix}_rank`);
        const divisionSelect = form.querySelector(`#${prefix}_division`);
        const starsInput = form.querySelector(`#${prefix}_stars`);
        const config = ranks[rankSelect.value];
        const previousDivision = divisionSelect.value;
        const previousStars = Number(starsInput.value);
        divisionSelect.innerHTML = '';
        for (let division = 1; division <= config.divisions; division += 1) {
            const label = config.divisions === 1 ? 'Tidak ada divisi' : roman[config.divisions - division];
            divisionSelect.add(new Option(label, division, false, String(division) === previousDivision));
        }
        const [minStars, maxStars] = starRange(rankSelect.value, config);
        starsInput.min = minStars;
        starsInput.max = maxStars;
        starsInput.value = Math.min(Math.max(previousStars, minStars), maxStars);
    };
    ['current', 'target'].forEach((prefix) => {
        form.querySelector(`#${prefix}_rank`).addEventListener('change', () => refresh(prefix));
    });
});
</script>
@endpush

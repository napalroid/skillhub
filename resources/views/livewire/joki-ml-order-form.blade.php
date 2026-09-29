<div class="joki-calculator">
    @php
        $currentHasDivisions = !in_array($current_rank, ['mythic', 'mythical_honor', 'mythical_glory', 'mythical_immortal'], true);
        $targetHasDivisions = !in_array($target_rank, ['mythic', 'mythical_honor', 'mythical_glory', 'mythical_immortal'], true);
        $currentMinStars = \App\Services\MlRankCalculator::getRelativeMinStars($current_rank);
        $currentMaxStars = \App\Services\MlRankCalculator::getRelativeMaxStars($current_rank);
        $targetMinStars = \App\Services\MlRankCalculator::getRelativeMinStars($target_rank);
        $targetMaxStars = \App\Services\MlRankCalculator::getRelativeMaxStars($target_rank);
    @endphp

    <section class="joki-form-section" aria-labelledby="rank-details-heading">
        <div class="joki-form-section__heading">
            <span class="joki-form-section__icon" aria-hidden="true">01</span>
            <div>
                <h2 id="rank-details-heading">Detail rank akun</h2>
                <p>Pilih rank saat ini dan tujuan untuk menghitung harga otomatis.</p>
            </div>
        </div>

        <div class="joki-rank-grid">
            <fieldset class="joki-rank-card">
                <legend>Rank saat ini</legend>
                <div class="joki-field-stack" wire:key="current-rank-fields-{{ $current_rank }}">
                    <div>
                        <label for="current_rank">Rank</label>
                        <select id="current_rank" name="current_rank" wire:model.live="current_rank" required>
                            @foreach($this->rankOptions as $key => $label)
                                <option value="{{ $key }}" @selected($current_rank === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('current_rank')<p class="joki-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="current_division">Divisi</label>
                        @if($currentHasDivisions)
                            <select id="current_division" name="current_division" wire:model.live="current_division" required>
                                @foreach($this->divisionOptions as $value => $roman)
                                    <option value="{{ $value }}" @selected((int) $current_division === (int) $value)>{{ $roman }}</option>
                                @endforeach
                            </select>
                        @else
                            <input id="current_division" type="text" value="—" readonly aria-label="Divisi tidak berlaku untuk rank ini">
                            <input type="hidden" name="current_division" value="{{ $current_division }}" wire:model.live="current_division">
                        @endif
                    </div>
                    <div>
                        <label for="current_stars">Bintang</label>
                        <input id="current_stars" name="current_stars" type="number" value="{{ $current_stars }}" min="{{ $currentMinStars }}" max="{{ $currentMaxStars }}" step="1" wire:model.live="current_stars" required>
                        <p class="joki-field-hint">Rentang: {{ $currentMinStars }}–{{ $currentMaxStars }} bintang</p>
                        @error('current_stars')<p class="joki-field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="joki-rank-card joki-rank-card--target">
                <legend>Rank tujuan</legend>
                <div class="joki-field-stack" wire:key="target-rank-fields-{{ $target_rank }}">
                    <div>
                        <label for="target_rank">Rank</label>
                        <select id="target_rank" name="target_rank" wire:model.live="target_rank" required>
                            @foreach($this->targetRankOptions as $key => $label)
                                <option value="{{ $key }}" @selected($target_rank === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('target_rank')<p class="joki-field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="target_division">Divisi</label>
                        @if($targetHasDivisions)
                            <select id="target_division" name="target_division" wire:model.live="target_division" required>
                                @foreach($this->targetDivisionOptions as $value => $roman)
                                    <option value="{{ $value }}" @selected((int) $target_division === (int) $value)>{{ $roman }}</option>
                                @endforeach
                            </select>
                        @else
                            <input id="target_division" type="text" value="—" readonly aria-label="Divisi tidak berlaku untuk rank ini">
                            <input type="hidden" name="target_division" value="{{ $target_division }}" wire:model.live="target_division">
                        @endif
                    </div>
                    <div>
                        <label for="target_stars">Bintang</label>
                        <input id="target_stars" name="target_stars" type="number" value="{{ $target_stars }}" min="{{ $targetMinStars }}" max="{{ $targetMaxStars }}" step="1" wire:model.live="target_stars" required>
                        <p class="joki-field-hint">Rentang: {{ $targetMinStars }}–{{ $targetMaxStars }} bintang</p>
                        @error('target_stars')<p class="joki-field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>
        </div>
    </section>

    <section class="joki-price-card" aria-labelledby="price-calculation-heading" aria-live="polite">
        <div class="joki-price-card__topline">
            <div>
                <p class="joki-eyebrow">Kalkulator live</p>
                <h2 id="price-calculation-heading">Perkiraan harga pesanan</h2>
            </div>
            <span class="joki-star-count">{{ $totalStars }} bintang</span>
        </div>

        @if($totalPrice !== null && $totalStars > 0)
            <div class="joki-breakdown">
                @foreach($priceBreakdown as $item)
                    <div class="joki-breakdown__row">
                        <span>{{ $item['rank'] }} <small>{{ $item['stars'] }} ★ × Rp{{ number_format($item['price_per_star'], 0, ',', '.') }}</small></span>
                        <strong>Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</strong>
                    </div>
                @endforeach
            </div>
            <div class="joki-total-price">
                <span>Total harga joki</span>
                <strong>Rp{{ number_format($totalPrice, 0, ',', '.') }}</strong>
            </div>
        @elseif($totalPrice === null)
            <p class="joki-price-notice">Harga per bintang untuk jasa ini belum dikonfigurasi seller.</p>
        @else
            <p class="joki-price-notice">Pilih rank tujuan yang lebih tinggi untuk melihat perkiraan harga.</p>
        @endif
        <p class="joki-price-caption">Harga final akan dihitung ulang secara aman saat pesanan dikirim.</p>
    </section>

</div>

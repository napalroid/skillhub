@if($service->activeAddons->isNotEmpty())
    @php($selectedAddonIds = array_map('intval', old('addon_ids', [])))
    <section class="service-addon-options" aria-labelledby="addon-options-heading">
        <div class="service-addon-options__heading">
            <div>
                <h2 id="addon-options-heading">Layanan tambahan</h2>
                <p>Opsional. Pilih layanan yang ingin ditambahkan ke pesanan ini.</p>
            </div>
            <span>Harga langsung ditambahkan</span>
        </div>

        <div class="service-addon-options__list">
            @foreach($service->activeAddons as $addon)
                <label class="service-addon-option">
                    <input type="checkbox"
                           name="addon_ids[]"
                           value="{{ $addon->id }}"
                           data-addon-input
                           data-addon-price="{{ $addon->price }}"
                           @checked(in_array($addon->id, $selectedAddonIds, true))>
                    <span class="service-addon-option__mark" aria-hidden="true">
                        <svg viewBox="0 0 16 16" fill="none"><path d="m3.5 8 3 3 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </span>
                    <span class="service-addon-option__copy">
                        <strong><svg aria-hidden="true" viewBox="0 0 24 24"><path d="m12 3 1.4 5.2L18 10l-4.6 1.8L12 17l-1.4-5.2L6 10l4.6-1.8L12 3Z" /><path d="m19 15 .6 2.4L22 18l-2.4.6L19 21l-.6-2.4L16 18l2.4-.6L19 15Z" /></svg>{{ $addon->name }}</strong>
                        @if($addon->description)<small class="service-addon-option__detail">{{ $addon->description }}</small>@endif
                    </span>
                    <span class="service-addon-option__price">+Rp{{ number_format($addon->price, 0, ',', '.') }}</span>
                </label>
            @endforeach
        </div>

        @error('addon_ids')<p class="service-addon-options__error">{{ $message }}</p>@enderror
    </section>

    <style>
        .service-addon-options { overflow: hidden; border: 1px solid #dfe2e7; border-radius: .75rem; background: #fff; }
        .service-addon-options__heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.25rem; border-bottom: 1px solid #dfe2e7; }
        .service-addon-options h2 { margin: 0; color: #111; font-size: 1.125rem; letter-spacing: -.02em; }
        .service-addon-options__heading p { margin: .4rem 0 0; color: #69717c; font-size: .8125rem; line-height: 1.55; }
        .service-addon-options__heading > span { color: #68707b; font-size: .75rem; line-height: 1.5rem; }
        .service-addon-options__list { padding: .75rem; }
        .service-addon-option { display: grid; grid-template-columns: 1.25rem minmax(0, 1fr) auto; gap: .85rem; align-items: center; min-height: 4.25rem; padding: .9rem .75rem; border: 1px solid transparent; border-radius: .6rem; cursor: pointer; transition: background-color .2s, border-color .2s, transform .15s; }
        .service-addon-option:hover { background: #f7f8fa; }
        .service-addon-option:active { transform: scale(.99); }
        .service-addon-option input { position: absolute; opacity: 0; }
        .service-addon-option__mark { display: grid; width: 1.25rem; height: 1.25rem; place-items: center; border: 1px solid #aeb6c0; border-radius: .3rem; color: transparent; transition: background-color .2s, border-color .2s, color .2s; }
        .service-addon-option__mark svg { width: .85rem; height: .85rem; }
        .service-addon-option input:checked + .service-addon-option__mark { border-color: #155eef; background: #155eef; color: #fff; }
        .service-addon-option:has(input:checked) { border-color: #cddbd4; background: #f3f8f5; }
        .service-addon-option input:focus-visible + .service-addon-option__mark { outline: 2px solid #155eef; outline-offset: 3px; }
        .service-addon-option__copy strong { display: flex; align-items: center; gap: .35rem; color: #111; font-size: .875rem; font-weight: 700; }
        .service-addon-option__copy strong svg { width: .9rem; height: .9rem; fill: none; stroke: #7a837d; stroke-width: 1.8; transition: stroke .2s, transform .25s cubic-bezier(.16,1,.3,1); }
        .service-addon-option input:checked ~ .service-addon-option__copy strong svg { stroke: #2f5d50; transform: rotate(16deg) scale(1.08); }
        .service-addon-option__copy small { display: block; margin-top: .18rem; color: #69717c; font-size: .75rem; line-height: 1.4; }
        .service-addon-option__detail { max-height: 0; margin-top: 0 !important; overflow: hidden; opacity: 0; transform: translateY(-.25rem); transition: max-height .3s cubic-bezier(.16,1,.3,1), margin .3s, opacity .2s, transform .3s cubic-bezier(.16,1,.3,1); }
        .service-addon-option input:checked ~ .service-addon-option__copy .service-addon-option__detail { max-height: 3rem; margin-top: .18rem !important; opacity: 1; transform: translateY(0); }
        .service-addon-option__price { color: #155eef; font-size: .8125rem; font-weight: 700; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .service-addon-options__error { margin: 0 1.25rem 1.25rem; color: #b42318; font-size: .75rem; font-weight: 600; }
        @media (prefers-reduced-motion: reduce) { .service-addon-options *, .service-addon-options *::before, .service-addon-options *::after { transition-duration: .01ms !important; } }
        @media (max-width: 640px) { .service-addon-options__heading { display: block; } .service-addon-options__heading > span { display: block; margin-top: .5rem; } }
    </style>
@endif

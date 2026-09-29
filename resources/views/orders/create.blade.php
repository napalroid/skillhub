<x-layouts.app title="Buat Pesanan - {{ $service->title }}">
    @vite(['resources/js/booking-calendar.jsx'])

    @php
        $portfolioImages = collect($service->portfolio_images ?? [])->filter()->take(3)->values();
        $coverImage = $service->image ? asset('storage/' . $service->image) : null;
        $rating = number_format((float) ($service->average_rating ?? 0), 1);
        $reviewCount = (int) ($service->reviews_count ?? 0);
        $isBarber = ($serviceType?->code ?? null) === 'barber';
        $haircutTypes = $isBarber ? ($service->barberService?->available_haircut_types ?? []) : [];
    @endphp

    <div class="order-page">
        <a class="order-back" href="{{ route('services.show', $service) }}"><span aria-hidden="true">←</span> Kembali ke Jasa</a>

        @if ($errors->any())
            <div class="order-alert" role="alert">Periksa kembali informasi pesanan yang ditandai sebelum melanjutkan.</div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="order-layout">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <input type="hidden" name="time_slot_id" id="selected_slot_id" value="{{ old('time_slot_id') }}">

            <main class="order-service" aria-labelledby="order-title">
                <section class="service-intro">
                    <div class="service-media">
                        @if($coverImage)
                            <img id="service-gallery-main" src="{{ $coverImage }}" alt="{{ $service->title }}" class="service-media__hero">
                        @else
                            <div class="service-media__placeholder" aria-hidden="true"><span>{{ strtoupper(mb_substr($service->title, 0, 1)) }}</span></div>
                        @endif
                    </div>

                    <div class="service-copy">
                        <span class="service-category">{{ $service->subcategory?->name ?? 'Jasa profesional' }}</span>
                        <h1 id="order-title">{{ $service->title }}</h1>

                        <dl class="service-meta">
                            <div><dt aria-hidden="true">★</dt><dd>{{ $rating }} <span>({{ $reviewCount }} ulasan)</span></dd></div>
                            <div><dt aria-hidden="true">◫</dt><dd>Pesanan terverifikasi</dd></div>
                            <div><dt aria-hidden="true">◷</dt><dd>{{ $isBarber && $service->barberService?->estimated_duration_minutes ? $service->barberService->estimated_duration_minutes . ' menit per sesi' : 'Jadwal fleksibel' }}</dd></div>
                        </dl>

                        <div class="seller-summary">
                            <div class="seller-avatar" aria-hidden="true">{{ strtoupper(mb_substr($service->seller?->name ?? 'S', 0, 1)) }}</div>
                            <div><span>Dari</span><strong>{{ $service->seller?->name ?? 'Penyedia Jasa' }}</strong><small><i></i> Online</small></div>
                        </div>

                        <p class="service-price">Rp{{ number_format($service->price, 0, ',', '.') }} <span>/ per pesanan</span></p>
                    </div>
                </section>

                @if($coverImage || $portfolioImages->isNotEmpty())
                    <div class="service-gallery" aria-label="Contoh hasil jasa">
                        @if($coverImage)<button type="button" class="gallery-thumb is-active" data-gallery-src="{{ $coverImage }}" aria-label="Tampilkan gambar utama"><img src="{{ $coverImage }}" alt=""></button>@endif
                        @foreach($portfolioImages as $image)
                            <button type="button" class="gallery-thumb" data-gallery-src="{{ asset('storage/' . $image) }}" aria-label="Tampilkan contoh portofolio"><img src="{{ asset('storage/' . $image) }}" alt=""></button>
                        @endforeach
                    </div>
                @endif

                <section class="service-benefits" aria-label="Keunggulan jasa">
                    <div><span aria-hidden="true">✦</span><strong>Proses jelas</strong><small>Detail pesanan tercatat</small></div>
                    <div><span aria-hidden="true">▣</span><strong>Hasil berkualitas</strong><small>Disusun dengan teliti</small></div>
                    <div><span aria-hidden="true">◌</span><strong>Komunikasi cepat</strong><small>Respon dalam satu hari</small></div>
                </section>

                <section class="about-service" aria-labelledby="about-service-title">
                    <h2 id="about-service-title">Tentang Jasa Ini</h2>
                    <p>{{ $service->description }}</p>
                </section>
            </main>

            <aside class="order-panel" aria-label="Informasi pesanan">
                @if($service->time_slots_enabled)
                    @if(count($availableSlots))
                        <div id="booking-calendar-root" data-available-slots="{{ json_encode($availableSlots) }}"></div>
                    @else
                        <section class="booking-empty-state"><strong>Jadwal belum tersedia</strong><p>Penyedia jasa belum menambahkan waktu yang bisa dipesan. Coba hubungi mereka terlebih dahulu.</p></section>
                    @endif
                @endif

                @include('orders.partials.addon-options')

                <section class="order-details" aria-labelledby="order-details-title">
                    <div class="order-details__heading">
                        <span class="booking-icon" aria-hidden="true">▤</span>
                        <div><h2 id="order-details-title">Informasi Pesanan</h2><p>Pastikan data berikut sudah benar.</p></div>
                    </div>

                    @if($service->booking_config && ($service->booking_config['enabled'] ?? false) && !empty($service->booking_config['fields']))
                    <div class="booking-fields">
                            @if($isBarber && !empty($haircutTypes))
                                <fieldset class="booking-field booking-field--choices">
                                    <legend>Pilih layanan utama <em>*</em></legend>
                                    <small>Tentukan jenis potongan agar barber dapat menyiapkan sesi yang sesuai.</small>
                                    <div class="booking-choice-grid">
                                        @foreach($haircutTypes as $type)
                                            <label class="booking-choice">
                                                <input type="radio" name="booking_data[haircut_type]" value="{{ $type }}" required @checked(old('booking_data.haircut_type') === $type)>
                                                <span><b>{{ $type }}</b><small>Termasuk konsultasi singkat</small></span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('booking_data.haircut_type')<p class="field-error">{{ $message }}</p>@enderror
                                </fieldset>
                            @endif
                            @foreach($service->booking_config['fields'] as $field)
                                @php($fieldKey = 'booking_data.' . $field['name'])
                                <div class="booking-field">
                                    <label for="booking_{{ $field['name'] }}">{{ $field['label'] }} @if($field['required'] ?? false)<em>*</em>@endif</label>
                                    @if($field['type'] === 'textarea')
                                        <textarea id="booking_{{ $field['name'] }}" name="booking_data[{{ $field['name'] }}]" rows="3" placeholder="{{ $field['placeholder'] ?? '' }}" {{ ($field['required'] ?? false) ? 'required' : '' }}>{{ old($fieldKey, $field['default'] ?? '') }}</textarea>
                                    @elseif($field['type'] === 'select')
                                        <select id="booking_{{ $field['name'] }}" name="booking_data[{{ $field['name'] }}]" {{ ($field['required'] ?? false) ? 'required' : '' }}><option value="">Pilih {{ $field['label'] }}</option>@foreach($field['options'] ?? [] as $option)<option value="{{ $option }}" @selected(old($fieldKey, $field['default'] ?? '') == $option)>{{ $option }}</option>@endforeach</select>
                                    @else
                                        <input id="booking_{{ $field['name'] }}" type="{{ in_array($field['type'], ['number', 'date', 'time'], true) ? $field['type'] : 'text' }}" name="booking_data[{{ $field['name'] }}]" value="{{ old($fieldKey, $field['default'] ?? '') }}" placeholder="{{ $field['placeholder'] ?? '' }}" maxlength="{{ $field['maxlength'] ?? 255 }}" {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                    @endif
                                    @if(!empty($field['help_text']))<small>{{ $field['help_text'] }}</small>@endif
                                    @error($fieldKey)<p class="field-error">{{ $message }}</p>@enderror
                                </div>
                            @endforeach
                        </div>
                    @elseif($isBarber && !empty($haircutTypes))
                        <div class="booking-fields">
                            <fieldset class="booking-field booking-field--choices">
                                <legend>Pilih layanan utama <em>*</em></legend>
                                <small>Tentukan jenis potongan agar barber dapat menyiapkan sesi yang sesuai.</small>
                                <div class="booking-choice-grid">
                                    @foreach($haircutTypes as $type)
                                        <label class="booking-choice">
                                            <input type="radio" name="booking_data[haircut_type]" value="{{ $type }}" required @checked(old('booking_data.haircut_type') === $type)>
                                            <span><b>{{ $type }}</b><small>Termasuk konsultasi singkat</small></span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('booking_data.haircut_type')<p class="field-error">{{ $message }}</p>@enderror
                            </fieldset>
                        </div>
                    @endif

                    <div class="booking-field booking-message">
                        <label for="message">{{ $isBarber ? 'Permintaan khusus' : 'Catatan' }} <span>opsional</span></label>
                        <textarea name="message" id="message" rows="3" maxlength="1000" placeholder="{{ $isBarber ? 'Contoh: alergi produk tertentu, preferensi gaya, atau referensi potongan.' : 'Contoh: warna yang diinginkan, referensi, dll...' }}">{{ old('message') }}</textarea>
                        @if($isBarber)<small>Tambahkan detail yang membantu barber menyiapkan layananmu.</small>@endif
                        @error('message')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </section>

                <footer class="order-panel__footer">
                    <div><span>Total Harga</span><strong id="order-estimated-total" data-base-price="{{ $service->price }}">Rp{{ number_format($service->price, 0, ',', '.') }}</strong></div>
                    <button type="submit" id="submitBtn" {{ $service->time_slots_enabled ? 'disabled' : '' }} class="order-submit"><span>{{ $service->time_slots_enabled ? 'Pilih Waktu Dulu' : 'Lanjutkan Pesanan' }}</span><b aria-hidden="true">→</b></button>
                </footer>
            </aside>
        </form>
    </div>

    <style>
        :root { --order-ink:#142033; --order-muted:#728197; --order-line:#dce5ef; --order-blue:#1568f5; --order-blue-soft:#eff6ff; --order-page:#f7fafc; --order-surface:#fff; --order-radius:10px; --order-shadow:0 16px 42px rgba(30,64,110,.10); --order-space:1rem; --order-motion:220ms cubic-bezier(.16,1,.3,1); }
        .order-page { position:relative; isolation:isolate; width:min(100% - 2rem, 1210px); margin:0 auto; padding:2rem 0 5rem; color:var(--order-ink); font-family:'Inter',ui-sans-serif,system-ui,sans-serif; }
        .order-page::before{content:'';position:absolute;z-index:-1;inset:4rem -8rem auto;height:29rem;pointer-events:none;background:radial-gradient(circle at 22% 28%,rgba(21,104,245,.10),transparent 30%),repeating-linear-gradient(90deg,transparent 0,transparent 39px,rgba(74,105,142,.055) 40px);mask-image:linear-gradient(to bottom,#000,transparent)}
        .order-back { display:inline-flex; align-items:center; gap:.55rem; min-height:44px; color:#40516a; font-size:.75rem; font-weight:700; text-decoration:none; } .order-back:hover{color:var(--order-blue)} .order-back span{font-size:1.05rem}
        .order-alert { margin:1rem 0; padding:.85rem 1rem; border:1px solid #fecaca; border-radius:var(--order-radius); background:#fff7f7; color:#b42318; font-size:.8rem; font-weight:600; }
        .order-layout { display:grid; grid-template-columns:minmax(0,1.35fr) minmax(420px,1fr); gap:2.5rem; align-items:start; margin-top:.8rem; }
        .service-intro { display:block; }
        .service-media { overflow:hidden; aspect-ratio:16/9; border-radius:var(--order-radius); background:var(--order-surface); } .service-media__hero { display:block; width:100%; height:100%; object-fit:cover; } .service-media__placeholder{display:grid;place-items:center;width:100%;height:100%;background:linear-gradient(135deg,#263950,#111827);color:#fff}.service-media__placeholder span{font-size:4rem;font-weight:800}
        .service-copy { margin-top:1.25rem; } .service-category { display:inline-block; padding:.32rem .6rem; border-radius:999px; background:#eaf2ff; color:#2672dc; font-size:.65rem; font-weight:800; } .service-copy h1{max-width:18ch;margin:.65rem 0 .7rem;font-size:clamp(1.55rem,2.5vw,2.1rem);line-height:1.18;letter-spacing:-.045em;font-weight:800}
        .service-meta{display:flex;flex-wrap:wrap;gap:.7rem 1rem;margin:1.15rem 0;padding:0}.service-meta div{display:flex;align-items:center;gap:.34rem}.service-meta dt{color:#f2ad27;font-size:.85rem}.service-meta dd{margin:0;color:#53637a;font-size:.69rem;font-weight:650}.service-meta dd span{color:#8390a2;font-weight:500}
        .seller-summary{display:flex;align-items:center;gap:.6rem}.seller-avatar{display:grid;place-items:center;width:2rem;height:2rem;border-radius:50%;background:#dce5f0;color:#304159;font-size:.75rem;font-weight:800}.seller-summary span,.seller-summary strong,.seller-summary small{display:block}.seller-summary span{color:#8290a2;font-size:.62rem}.seller-summary strong{font-size:.7rem}.seller-summary small{margin-top:.12rem;color:#11a96f;font-size:.62rem;font-weight:700}.seller-summary i{display:inline-block;width:.36rem;height:.36rem;border-radius:50%;background:currentColor}.service-price{margin:1.15rem 0 0;font-size:1.2rem;font-weight:800;letter-spacing:-.04em}.service-price span{color:#8090a4;font-size:.65rem;font-weight:600;letter-spacing:0}
        .service-gallery{display:flex;gap:.65rem;margin-top:.65rem}.gallery-thumb{width:4.45rem;height:3.35rem;padding:0;overflow:hidden;border:1px solid var(--order-line);border-radius:6px;background:var(--order-surface);cursor:pointer}.gallery-thumb.is-active{border:2px solid var(--order-blue)}.gallery-thumb img{display:block;width:100%;height:100%;object-fit:cover}
        .service-benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;margin:2.5rem 0;padding:1.45rem 0;border-block:1px solid var(--order-line)}.service-benefits>div{display:grid;grid-template-columns:1.5rem 1fr;column-gap:.55rem}.service-benefits span{grid-row:span 2;color:#365a84;font-size:1rem}.service-benefits strong{font-size:.68rem}.service-benefits small{margin-top:.2rem;color:var(--order-muted);font-size:.62rem;line-height:1.35}.about-service h2{margin:0 0 .65rem;font-size:.95rem;letter-spacing:-.02em}.about-service p{max-width:75ch;margin:0;color:#617188;font-size:.78rem;line-height:1.8}
        .order-panel{position:sticky;top:5.25rem;overflow:hidden;border:1px solid #e5ebf3;border-radius:var(--order-radius);background:var(--order-surface);box-shadow:var(--order-shadow)}.booking-slots,.order-details{padding:1.1rem}.booking-slots__heading,.order-details__heading{display:flex;gap:.6rem;align-items:flex-start}.booking-icon{display:grid;place-items:center;flex:0 0 1.4rem;width:1.4rem;height:1.4rem;border:1px solid #cfd9e6;border-radius:5px;color:#435772;font-size:.85rem}.booking-slots h2,.order-details h2{margin:0;font-size:.84rem;letter-spacing:-.02em}.booking-slots__heading p,.order-details__heading p{margin:.15rem 0 0;color:#8a98ab;font-size:.61rem;line-height:1.4}
        .booking-date-nav{display:grid;grid-template-columns:2rem 1fr 2rem;align-items:center;margin-top:.9rem;border:1px solid var(--order-line);border-radius:6px}.booking-date-nav__label{text-align:center;color:#2f4058;font-size:.64rem;font-weight:750}.booking-nav-button{width:2rem;height:1.85rem;border:0;background:transparent;color:#263b58;font-size:1.35rem;cursor:pointer}.booking-nav-button:disabled{opacity:.3;cursor:not-allowed}.booking-dates{display:grid;grid-template-columns:repeat(5,1fr);gap:.35rem;margin-top:.65rem}.booking-date{min-height:2.85rem;padding:.32rem .1rem;border:1px solid var(--order-line);border-radius:6px;background:#fff;color:#637187;cursor:pointer}.booking-date span,.booking-date strong{display:block}.booking-date span{font-size:.55rem}.booking-date strong{margin-top:.13rem;font-size:.59rem}.booking-date.is-selected{border-color:var(--order-blue);background:#f4f8ff;color:#1661e2;box-shadow:inset 0 0 0 1px var(--order-blue)}.booking-time-label{margin:.9rem 0 .45rem;color:#3d4e65;font-size:.64rem;font-weight:750}.booking-times{display:grid;grid-template-columns:repeat(4,1fr);gap:.42rem}.booking-time{min-height:1.85rem;border:1px solid var(--order-line);border-radius:6px;background:#fff;color:#596b82;font-size:.62rem;font-weight:700;cursor:pointer}.booking-time.is-selected{border-color:var(--order-blue);background:#f4f8ff;color:#1261ee;box-shadow:inset 0 0 0 1px var(--order-blue)}.booking-empty,.booking-empty-state p{color:var(--order-muted);font-size:.7rem;line-height:1.5}.booking-empty-state{padding:1.25rem}.booking-empty-state strong{font-size:.84rem}
        .order-panel .service-addon-options{margin:0;border:0;border-top:1px solid var(--order-line);border-radius:0}.order-panel .service-addon-options__heading{padding:1.05rem 1.1rem .65rem;border:0}.order-panel .service-addon-options h2{font-size:.79rem}.order-panel .service-addon-options__heading p{font-size:.61rem}.order-panel .service-addon-options__heading>span{display:none}.order-panel .service-addon-options__list{padding:.15rem .7rem .7rem}.order-panel .service-addon-option{min-height:3.5rem;padding:.55rem .4rem;gap:.6rem}.order-panel .service-addon-option__copy strong{font-size:.68rem}.order-panel .service-addon-option__copy small{font-size:.58rem}.order-panel .service-addon-option__price{color:#52657e;font-size:.62rem}.order-panel .service-addon-option__mark{width:1rem;height:1rem}.order-panel .service-addon-option__mark svg{width:.68rem;height:.68rem}
        .order-details{border-top:1px solid var(--order-line)}.booking-fields{display:grid;gap:.75rem;margin-top:.9rem}.booking-field{margin-top:.85rem}.booking-field label{display:block;margin-bottom:.38rem;color:#5a6b82;font-size:.63rem;font-weight:700}.booking-field label em{color:#dc2626;font-style:normal}.booking-field label span{color:#94a0af;font-weight:500}.booking-field input,.booking-field select,.booking-field textarea{box-sizing:border-box;width:100%;border:1px solid var(--order-line);border-radius:6px;background:#fff;color:#26364d;font:600 .67rem 'Inter',sans-serif;outline:none;padding:.6rem .7rem;transition:border-color .18s,box-shadow .18s}.booking-field textarea{resize:vertical;line-height:1.5}.booking-field input::placeholder,.booking-field textarea::placeholder{color:#a0adbc;font-weight:500}.booking-field input:focus,.booking-field select:focus,.booking-field textarea:focus{border-color:var(--order-blue);box-shadow:0 0 0 3px rgba(21,104,245,.13)}.booking-field small{display:block;margin-top:.28rem;color:#8a98ab;font-size:.58rem}.field-error{margin:.32rem 0 0;color:#c5221f;font-size:.6rem;font-weight:700}
        .booking-field--choices{padding:0;border:0}.booking-field--choices legend{margin-bottom:.38rem;color:#5a6b82;font-size:.63rem;font-weight:700}.booking-field--choices legend em{color:#dc2626;font-style:normal}.booking-choice-grid{display:grid;gap:.45rem;margin-top:.65rem}.booking-choice{display:grid;grid-template-columns:1rem 1fr;gap:.58rem;align-items:center;min-height:3.1rem;padding:.55rem .65rem;border:1px solid transparent;border-radius:6px;cursor:pointer;transition:background var(--order-motion),border-color var(--order-motion),transform var(--order-motion)}.booking-choice:hover{background:#f5f8fc}.booking-choice:has(input:checked){border-color:#9bc3ff;background:var(--order-blue-soft)}.booking-choice input{width:1rem;height:1rem;accent-color:var(--order-blue)}.booking-choice b,.booking-choice small{display:block}.booking-choice b{font-size:.66rem}.booking-choice small{margin-top:.12rem}.booking-choice:active{transform:scale(.99)}
        .order-panel__footer{display:grid;grid-template-columns:1fr 1.45fr;gap:.8rem;align-items:center;padding:1rem 1.1rem;border-top:1px solid var(--order-line);background:#fbfdff}.order-panel__footer span,.order-panel__footer strong{display:block}.order-panel__footer span{color:#627188;font-size:.58rem}.order-panel__footer strong{margin-top:.15rem;font-size:.83rem}.order-submit{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;min-height:2.55rem;border:0;border-radius:6px;background:var(--order-blue);color:#fff;font-size:.62rem;font-weight:800;box-shadow:0 7px 14px rgba(21,104,245,.19);cursor:pointer;transition:transform .18s,background .18s}.order-submit:hover{background:#0757d9}.order-submit:active{transform:translateY(1px)}.order-submit:disabled{background:#aab9ce;box-shadow:none;cursor:not-allowed}
        .order-page :focus-visible{outline:2px solid var(--order-blue);outline-offset:2px}@media(max-width:960px){.order-layout{grid-template-columns:1fr;gap:2rem}.order-panel{position:static}}@media(max-width:600px){.order-page{width:min(100% - 1.25rem,1210px);padding-top:1rem}.service-media{aspect-ratio:16/10}.service-copy h1{font-size:1.7rem}.service-benefits{grid-template-columns:1fr;gap:1rem;margin:1.8rem 0}.booking-dates{gap:.25rem}.order-panel__footer{position:sticky;bottom:0}.service-meta{gap:.5rem}.order-submit{font-size:.58rem}}
        @media(prefers-reduced-motion:reduce){.order-page *{scroll-behavior:auto!important;transition-duration:.01ms!important}}
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const totalElement = document.getElementById('order-estimated-total');
            const addonInputs = document.querySelectorAll('[data-addon-input]');
            const submitButton = document.getElementById('submitBtn');
            const slotInput = document.getElementById('selected_slot_id');
            const timeSlotsEnabled = {{ $service->time_slots_enabled ? 'true' : 'false' }};
            const formatRupiah = new Intl.NumberFormat('id-ID');
            const mainImage = document.getElementById('service-gallery-main');

            const updateTotal = () => {
                const addonsTotal = Array.from(addonInputs).filter((input) => input.checked).reduce((total, input) => total + Number(input.dataset.addonPrice || 0), 0);
                totalElement.textContent = `Rp${formatRupiah.format(Number(totalElement.dataset.basePrice || 0) + addonsTotal)}`;
            };
            const updateSubmitButton = (slotId) => {
                if (!timeSlotsEnabled) return;
                const isReady = Boolean(slotId);
                submitButton.disabled = !isReady;
                submitButton.querySelector('span').textContent = isReady ? 'Lanjutkan Pesanan' : 'Pilih Waktu Dulu';
            };
            addonInputs.forEach((input) => input.addEventListener('change', updateTotal));
            window.updateSubmitButton = updateSubmitButton;
            slotInput?.addEventListener('change', () => updateSubmitButton(slotInput.value));
            document.querySelectorAll('[data-gallery-src]').forEach((button) => button.addEventListener('click', () => {
                if (!mainImage) return;
                mainImage.src = button.dataset.gallerySrc;
                document.querySelectorAll('[data-gallery-src]').forEach((item) => item.classList.toggle('is-active', item === button));
            }));
            updateTotal();
            updateSubmitButton(slotInput?.value);
        });
    </script>
</x-layouts.app>

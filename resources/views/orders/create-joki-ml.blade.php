@extends('layouts.app')

@section('title', 'Buat Pesanan Joki ML - SkillHub')

@livewireStyles

@section('content')
@php
    $portfolioImages = collect($service->portfolio_images ?? [])->filter()->values();
    // Prefer seller's portfolio photo when no dedicated service cover was uploaded.
    $coverPath = $service->image ?: $portfolioImages->first();
    $coverImage = $coverPath ? asset('storage/' . $coverPath) : null;
@endphp
<div class="joki-order-page">
    <a href="{{ route('services.show', $service->id) }}" class="joki-back-link"><span aria-hidden="true">←</span> Kembali ke jasa</a>

    <form method="POST" action="{{ route('orders.store-joki-ml') }}" class="joki-order-layout">
        @csrf
        <input type="hidden" name="service_id" value="{{ $service->id }}">

        @if ($errors->any())
            <div class="joki-order-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <main class="joki-service-summary">
            <section class="joki-service-intro">
            <div class="joki-service-media">
                @if($coverImage)
                    <img id="joki-gallery-main" src="{{ $coverImage }}" alt="{{ $service->title }}">
                @else
                    <div class="joki-service-media__fallback" aria-hidden="true"><span>ML</span><small>BOOST</small></div>
                @endif
            </div>

            <div class="joki-service-overview">
                <span class="joki-service-badge">Joki Mobile Legends</span>
                <h1>{{ $service->title }}</h1>
                <div class="joki-seller-line">
                    <span class="joki-seller-avatar" aria-hidden="true">{{ strtoupper(mb_substr($service->seller->name, 0, 1)) }}</span>
                    <div><small>Dikerjakan oleh</small><strong>{{ $service->seller->name }}</strong></div>
                </div>
                <div class="joki-starting-price"><small>Harga per bintang mulai dari</small><strong>Rp{{ number_format($service->price, 0, ',', '.') }}</strong></div>
            </div>
            </section>

            <section class="joki-portfolio" aria-label="Contoh hasil jasa">
                <div class="joki-portfolio__list">
                    @forelse($portfolioImages as $image)
                        <button type="button" class="joki-portfolio__image @if($image === $coverPath) is-active @endif" data-joki-gallery-src="{{ asset('storage/' . $image) }}" aria-label="Tampilkan gambar portofolio {{ $loop->iteration }}"><img src="{{ asset('storage/' . $image) }}" alt="Portofolio {{ $service->title }} {{ $loop->iteration }}"></button>
                    @empty
                        <div class="joki-portfolio__empty">Tidak ada gambar portofolio.</div>
                    @endforelse
                </div>
            </section>

            <section class="joki-trust-row" aria-label="Keunggulan jasa">
                <div><span>✓</span><p><strong>Harga transparan</strong>Perhitungan otomatis per bintang.</p></div>
                <div><span>✓</span><p><strong>Akun lebih aman</strong>Data akun hanya untuk pengerjaan.</p></div>
                <div><span>✓</span><p><strong>Komunikasi jelas</strong>Ikuti progres dari detail order.</p></div>
            </section>

            <section class="joki-about-service" aria-labelledby="about-service-heading">
                <h2 id="about-service-heading">Tentang jasa ini</h2>
                <p>{{ $service->description ?: 'Pilih rank awal dan rank tujuan untuk melihat estimasi harga sebelum mengirim pesanan.' }}</p>
            </section>
        </main>

        <aside class="joki-order-panel" aria-label="Form pesanan joki Mobile Legends">
            <div class="joki-order-panel__header">
                <p class="joki-eyebrow">Pesan jasa</p>
                <h2>Siapkan pesananmu</h2>
                <p>Tentukan target rank, lalu isi data akun untuk seller.</p>
            </div>
            <div class="joki-order-panel__body">
                <livewire:joki-ml-order-form :joki-ml-service="$service->jokiMlService" />

                <section class="joki-form-section joki-account-section" aria-labelledby="account-details-heading">
                    <div class="joki-form-section__heading">
                        <span class="joki-form-section__icon" aria-hidden="true">02</span>
                        <div>
                            <h2 id="account-details-heading">Detail akun ML</h2>
                            <p>Data ini digunakan seller hanya untuk mengerjakan pesanan buyer.</p>
                        </div>
                    </div>
                    <div class="joki-field-stack">
                        <div>
                            <label for="ml_username">Username / ID Mobile Legends</label>
                            <input id="ml_username" name="ml_username" type="text" value="{{ old('ml_username') }}" placeholder="Contoh: NamaPlayer (12345678)" required autocomplete="username">
                            @error('ml_username')<p class="joki-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="ml_password">Password akun</label>
                            <input id="ml_password" name="ml_password" type="password" placeholder="Masukkan password akun" required autocomplete="current-password">
                            <p class="joki-field-hint">Informasi akun disimpan terenkripsi dan hanya dipakai untuk pengerjaan pesanan.</p>
                            @error('ml_password')<p class="joki-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="hero_notes">Catatan hero <span>(opsional)</span></label>
                            <textarea id="hero_notes" name="hero_notes" rows="3" placeholder="Contoh: hero favorit, hero yang tidak boleh digunakan, atau preferensi role.">{{ old('hero_notes') }}</textarea>
                        </div>
                        <div>
                            <label for="additional_notes">Catatan tambahan <span>(opsional)</span></label>
                            <textarea id="additional_notes" name="additional_notes" rows="3" placeholder="Contoh: waktu yang tidak boleh dimainkan atau instruksi lain.">{{ old('additional_notes') }}</textarea>
                        </div>
                    </div>
                </section>
                @include('orders.partials.addon-options')
            </div>
            <div class="joki-order-panel__footer">
                <p><span>Harga final</span>Dihitung ulang saat pesanan dikirim.</p>
                <button type="submit" class="joki-submit-button">Lanjutkan Pesanan <span aria-hidden="true">→</span></button>
            </div>
        </aside>
    </form>
</div>

<style>
    .joki-order-page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem 4rem; color: #152033; }
    .joki-back-link { display: inline-flex; align-items: center; gap: .7rem; margin: 0 0 1.4rem; color: #344054; font-size: .82rem; font-weight: 700; text-decoration: none; }.joki-back-link:hover { color: #155eef; }
    .joki-back-link:focus-visible, .joki-submit-button:focus-visible, .joki-order-page :is(input, select, textarea):focus-visible { outline: 3px solid rgba(21, 94, 239, .28); outline-offset: 2px; }
    .joki-order-layout { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(360px, .82fr); gap: 2.75rem; align-items: start; }.joki-order-error { grid-column:1 / -1; margin:0; padding:.75rem .9rem; border:1px solid #fecaca; border-radius:.55rem; background:#fff7f7; color:#b42318; font-size:.78rem; font-weight:700; }.joki-service-summary { min-width: 0; }
    .joki-service-media { overflow: hidden; aspect-ratio: 16 / 9; border: 0; border-radius: .8rem; background: #fff; box-shadow: none; }.joki-service-media img { display: block; width: 100%; height: 100%; object-fit: cover; }
    .joki-service-media__fallback { display: grid; height: 100%; place-content: center; text-align: center; color: #eef5ff; background: radial-gradient(circle at 50% 35%, #1f5ca6 0, #0a203f 32%, #070d18 70%); }.joki-service-media__fallback span { font-size: clamp(4rem, 12vw, 7.5rem); font-weight: 900; letter-spacing: -.08em; line-height: .75; }.joki-service-media__fallback small { margin-top: 1rem; color: #9cc6ff; font-size: .75rem; font-weight: 800; letter-spacing: .38em; }
    .joki-service-overview { padding: 1.7rem 0 1.2rem; }.joki-service-badge, .joki-eyebrow { display: inline-block; margin: 0 0 .55rem; color: #155eef; font-size: .7rem; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; }.joki-service-badge { padding: .35rem .55rem; border-radius: 999px; background: #eaf2ff; }
    .joki-service-overview h1 { max-width: 18ch; margin: 0; color: #101828; font-size: clamp(1.55rem, 2.5vw, 2.1rem); letter-spacing: -.045em; line-height: 1.18; }
    .joki-seller-line { display: flex; align-items: center; gap: .7rem; margin: 1.1rem 0; }.joki-seller-avatar { display: grid; width: 2.35rem; height: 2.35rem; place-items: center; border-radius: 50%; background: #eaf2ff; color: #155eef; font-size: .82rem; font-weight: 800; }.joki-seller-line small, .joki-starting-price small { display: block; color: #98a2b3; font-size: .67rem; font-weight: 700; }.joki-seller-line strong { display: block; color: #344054; font-size: .8rem; }.joki-starting-price { margin-top: 1.35rem; }.joki-starting-price strong { display: block; margin-top: .2rem; color: #101828; font-size: 1.55rem; letter-spacing: -.04em; }
    .joki-trust-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: .9rem; padding: 1.4rem 0; border-top: 1px solid #eaecf0; border-bottom: 1px solid #eaecf0; }.joki-trust-row > div { display: flex; gap: .55rem; align-items: flex-start; }.joki-trust-row > div > span { display: grid; flex: 0 0 auto; width: 1.3rem; height: 1.3rem; place-items: center; border-radius: 50%; background: #eaf2ff; color: #155eef; font-size: .72rem; font-weight: 900; }.joki-trust-row p { margin: 0; color: #667085; font-size: .67rem; line-height: 1.5; }.joki-trust-row strong { display: block; color: #344054; font-size: .72rem; }.joki-about-service { padding: 1.4rem 0; }.joki-about-service h2 { margin: 0; color: #101828; font-size: 1rem; letter-spacing: -.02em; }.joki-about-service p { max-width: 650px; margin: .6rem 0 0; color: #667085; font-size: .83rem; line-height: 1.7; }
    .joki-order-panel { overflow: hidden; border: 1px solid #e1e7ef; border-radius: .9rem; background: #fff; box-shadow: 0 18px 38px rgba(16, 24, 40, .1); }.joki-order-panel__header { padding: 1.35rem 1.35rem 1.1rem; border-bottom: 1px solid #eaecf0; }.joki-order-panel__header .joki-eyebrow { margin-bottom: .3rem; }.joki-order-panel__header h2 { margin: 0; color: #101828; font-size: 1.15rem; letter-spacing: -.03em; }.joki-order-panel__header > p:last-child { margin: .35rem 0 0; color: #667085; font-size: .75rem; line-height: 1.5; }.joki-order-panel__body { padding: 1.1rem; }
    .joki-calculator { display: grid; gap: .9rem; }.joki-form-section, .joki-price-card { padding: 1rem; border: 1px solid #e4e8ee; border-radius: .65rem; background: #fff; }.joki-account-section { margin-top: .9rem; }.joki-form-section__heading { display: flex; gap: .65rem; align-items: flex-start; margin-bottom: 1rem; }.joki-form-section__icon { display: grid; flex: 0 0 auto; width: 1.65rem; height: 1.65rem; place-items: center; border-radius: .45rem; background: #eaf2ff; color: #155eef; font-size: .65rem; font-weight: 800; }.joki-form-section h2, .joki-price-card h2 { margin: 0; color: #1d2939; font-size: .88rem; letter-spacing: -.015em; }.joki-form-section__heading p { margin: .18rem 0 0; color: #667085; font-size: .67rem; line-height: 1.45; }
    .joki-rank-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; }.joki-rank-card { min-width: 0; margin: 0; padding: .8rem; border: 1px solid #e4e8ee; border-radius: .55rem; }.joki-rank-card--target { border-color: #b7d2ff; background: #f8fbff; }.joki-rank-card legend { padding: 0 .25rem; color: #344054; font-size: .72rem; font-weight: 800; }.joki-field-stack { display: grid; gap: .75rem; }.joki-order-page label { display: block; margin-bottom: .32rem; color: #344054; font-size: .69rem; font-weight: 750; }.joki-order-page label span { color: #98a2b3; font-weight: 600; }
    .joki-order-page :is(input, select, textarea) { box-sizing: border-box; width: 100%; border: 1px solid #d8dee8; border-radius: .42rem; background: #fff; color: #1d2939; font: inherit; font-size: .76rem; transition: border-color .15s, box-shadow .15s; }.joki-order-page :is(input, select) { height: 2.35rem; padding: 0 .65rem; }.joki-order-page textarea { min-height: 4.75rem; padding: .62rem .65rem; line-height: 1.45; resize: vertical; }.joki-order-page :is(input, select, textarea):hover { border-color: #a7b7cd; }.joki-order-page :is(input, select, textarea):focus { border-color: #155eef; box-shadow: 0 0 0 3px rgba(21, 94, 239, .1); outline: 0; }.joki-order-page input[readonly] { color: #98a2b3; background: #f8fafc; cursor: default; }.joki-field-hint, .joki-field-error { margin: .3rem 0 0; font-size: .62rem; line-height: 1.4; }.joki-field-hint { color: #98a2b3; }.joki-field-error { color: #d92d20; font-weight: 700; }
    .joki-price-card { border-color: #bfdbfe; background: linear-gradient(135deg, #f7fbff, #eef6ff); }.joki-price-card__topline, .joki-total-price, .joki-breakdown__row { display: flex; justify-content: space-between; gap: .8rem; align-items: flex-start; }.joki-price-card .joki-eyebrow { margin-bottom: .16rem; font-size: .6rem; }.joki-star-count { padding: .33rem .5rem; border-radius: 999px; background: #dbeafe; color: #155eef; font-size: .65rem; font-weight: 800; white-space: nowrap; }.joki-breakdown { display: grid; gap: .48rem; margin-top: .9rem; }.joki-breakdown__row { padding-bottom: .48rem; border-bottom: 1px solid #dbeafe; color: #475467; font-size: .69rem; }.joki-breakdown__row small { display: block; margin-top: .14rem; color: #98a2b3; font-size: .59rem; }.joki-breakdown__row strong { color: #344054; font-size: .68rem; white-space: nowrap; }.joki-total-price { margin-top: .65rem; padding-top: .7rem; color: #344054; font-size: .75rem; font-weight: 750; }.joki-total-price strong { color: #155eef; font-size: 1.18rem; letter-spacing: -.04em; }.joki-price-notice, .joki-price-caption { margin: .8rem 0 0; color: #667085; font-size: .67rem; line-height: 1.5; }.joki-price-caption { color: #98a2b3; }
    .joki-order-panel__footer { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; gap: .8rem; padding: .95rem 1.1rem; border-top: 1px solid #eaecf0; background: #fbfcfe; }.joki-order-panel__footer p { margin: 0; color: #98a2b3; font-size: .62rem; line-height: 1.45; }.joki-order-panel__footer p span { display: block; color: #344054; font-size: .67rem; font-weight: 800; }.joki-submit-button { display: inline-flex; align-items: center; justify-content: center; gap: .55rem; min-height: 2.55rem; padding: 0 1rem; border: 0; border-radius: .45rem; background: #155eef; color: #fff; cursor: pointer; font-size: .7rem; font-weight: 800; box-shadow: 0 5px 12px rgba(21, 94, 239, .2); transition: background .15s, transform .15s; }.joki-submit-button:hover { background: #004eeb; }.joki-submit-button:active { transform: translateY(1px); }
    @media (max-width: 900px) { .joki-order-layout { grid-template-columns: 1fr; } .joki-order-panel { max-width: 650px; } } @media (max-width: 560px) { .joki-order-page { padding: 1.25rem .85rem 2.5rem; } .joki-rank-grid, .joki-trust-row, .joki-order-panel__footer { grid-template-columns: 1fr; } .joki-trust-row { gap: .8rem; } .joki-submit-button { width: 100%; } }
    /* Wide desktop checkout: less outer whitespace and less vertical scrolling. */
    /* Shared checkout cadence: Joki keeps its live calculator, but uses the
       same visual shell, column rhythm, and premium background as every service. */
    .joki-order-page { position:relative; isolation:isolate; width:min(100% - 2rem,1210px); max-width:none; padding:2rem 0 5rem; }
    .joki-order-page::before { content:''; position:absolute; z-index:-1; inset:4rem -8rem auto; height:29rem; pointer-events:none; background:radial-gradient(circle at 22% 28%,rgba(21,104,245,.10),transparent 30%),repeating-linear-gradient(90deg,transparent 0,transparent 39px,rgba(74,105,142,.055) 40px); mask-image:linear-gradient(to bottom,#000,transparent); }
    .joki-order-layout { grid-template-columns:minmax(0,1.35fr) minmax(420px,1fr); gap:2.5rem; }
    .joki-service-intro { display:block; }
    .joki-service-media { aspect-ratio:16 / 9; }
    .joki-service-overview { padding:0; margin-top:1.25rem; }
    .joki-portfolio { margin-top:.65rem; padding:0; border:0; border-radius:0; }
    .joki-portfolio__list { display:flex; flex-wrap:wrap; gap:.65rem; }
    .joki-portfolio__image { width:4.45rem; height:3.35rem; padding:0; overflow:hidden; border:1px solid #dce5ef; border-radius:6px; background:#fff; cursor:pointer; }
    .joki-portfolio__image.is-active { border:2px solid #155eef; }
    .joki-portfolio__image img { display:block; width:100%; height:100%; object-fit:cover; }
    .joki-portfolio__empty { display:grid; min-height:3.35rem; flex:1; place-items:center; border:1px dashed #cbd5e1; border-radius:.38rem; color:#98a2b3; font-size:.67rem; }
    .joki-trust-row { margin-top:.9rem; padding:1rem 0; }
    .joki-about-service { padding:.95rem 0; }
    .joki-order-panel__body { padding:.8rem; }
    .joki-account-section .joki-field-stack { grid-template-columns:1fr 1fr; column-gap:.9rem; }
    @media (max-width:1100px) { .joki-order-layout { grid-template-columns:1fr; } .joki-order-panel { max-width:760px; } }
    @media (max-width:720px) { .joki-service-media { aspect-ratio:16/10; } }
    @media (max-width:560px) { .joki-order-page { width:min(100% - 1.5rem, 1360px); padding:1rem 0 2rem; } .joki-account-section .joki-field-stack { grid-template-columns:1fr; } }
</style>
@endsection

@push('scripts')
    @livewireScripts
    <script>
        document.querySelectorAll('[data-joki-gallery-src]').forEach((button) => button.addEventListener('click', () => {
            const mainImage = document.getElementById('joki-gallery-main');
            if (!mainImage) return;
            mainImage.src = button.dataset.jokiGallerySrc;
            document.querySelectorAll('[data-joki-gallery-src]').forEach((item) => item.classList.toggle('is-active', item === button));
        }));
    </script>
@endpush

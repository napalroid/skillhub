@extends('layouts.app')

@section('title', 'Layanan Tambahan | SkillHub')

@section('content')
@php
    $addonRows = old('addons', $addons->map(fn ($addon) => [
        'id' => $addon->id,
        'name' => $addon->name,
        'description' => $addon->description,
        'price' => (float) $addon->price,
        'is_active' => $addon->is_active,
    ])->values()->all());
@endphp

<div class="addon-settings" x-data="serviceAddonEditor(@js($addonRows))">
    <div class="addon-settings__shell">
        <header class="addon-settings__header">
            <a href="{{ route('services.edit', $service) }}">← Kembali ke pengaturan jasa</a>
            <p>Layanan tambahan</p>
            <h1>{{ $service->title }}</h1>
            <span>Tambahkan pilihan berbayar yang buyer dapat pilih saat membuat pesanan.</span>
        </header>

        @if(session('success'))
            <p class="addon-notice" role="status">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('services.addons.update', $service) }}" class="addon-form">
            @csrf
            @method('PUT')

            <div class="addon-form__intro">
                <div>
                    <h2>Daftar layanan</h2>
                    <p>Contoh: Creambath, cuci rambut, pengerjaan express, atau revisi tambahan.</p>
                </div>
                <button type="button" class="addon-button addon-button--secondary" @click="addRow()">Tambah layanan</button>
            </div>

            <div class="addon-empty" x-show="rows.length === 0" x-cloak>
                <p>Belum ada layanan tambahan.</p>
                <span>Tambahkan hanya layanan yang benar-benar tersedia untuk jasa ini.</span>
            </div>

            <div class="addon-list" x-show="rows.length > 0">
                <template x-for="(addon, index) in rows" :key="addon.key">
                    <article class="addon-row">
                        <input type="hidden" :name="`addons[${index}][id]`" :value="addon.id || ''">
                        <div class="addon-row__number" x-text="String(index + 1).padStart(2, '0')"></div>
                        <div class="addon-field addon-field--name">
                            <label :for="`addon-name-${addon.key}`">Nama layanan</label>
                            <input type="text" :id="`addon-name-${addon.key}`" :name="`addons[${index}][name]`" x-model="addon.name" maxlength="100" required placeholder="Contoh: Creambath">
                            @error('addons.*.name')<em>{{ $message }}</em>@enderror
                        </div>
                        <div class="addon-field addon-field--price">
                            <label :for="`addon-price-${addon.key}`">Harga tambahan</label>
                            <div class="addon-price-input"><span>Rp</span><input type="number" :id="`addon-price-${addon.key}`" :name="`addons[${index}][price]`" x-model="addon.price" min="0" max="9999999999.99" step="1000" inputmode="numeric" required placeholder="25000"></div>
                            @error('addons.*.price')<em>{{ $message }}</em>@enderror
                        </div>
                        <div class="addon-field addon-field--description">
                            <label :for="`addon-description-${addon.key}`">Keterangan <span>opsional</span></label>
                            <input type="text" :id="`addon-description-${addon.key}`" :name="`addons[${index}][description]`" x-model="addon.description" maxlength="255" placeholder="Contoh: Termasuk pijat kepala">
                        </div>
                        <div class="addon-row__actions">
                            <label class="addon-switch" :for="`addon-active-${addon.key}`">
                                <input type="hidden" :name="`addons[${index}][is_active]`" value="0">
                                <input type="checkbox" :id="`addon-active-${addon.key}`" :name="`addons[${index}][is_active]`" value="1" x-model="addon.is_active">
                                <span aria-hidden="true"></span>
                                <b x-text="addon.is_active ? 'Aktif' : 'Nonaktif'"></b>
                            </label>
                            <button type="button" class="addon-remove" @click="removeRow(index)" :aria-label="`Hapus ${addon.name || 'layanan tambahan'}`">Hapus</button>
                        </div>
                    </article>
                </template>
            </div>

            @error('addons')<p class="addon-error">{{ $message }}</p>@enderror

            <footer class="addon-form__footer">
                <a href="{{ route('services.edit', $service) }}">Batal</a>
                <button type="submit" class="addon-button">Simpan layanan tambahan</button>
            </footer>
        </form>
    </div>
</div>

<style>
    .addon-settings { min-height: 100vh; background: #f7f8fa; color: #111; }
    .addon-settings__shell { width: min(100% - 2.5rem, 980px); margin: auto; padding: 3rem 0 5rem; }
    .addon-settings__header { padding-bottom: 2.5rem; border-bottom: 1px solid #dfe2e7; }
    .addon-settings__header > a { color: #68707b; font-size: .82rem; text-decoration: none; }
    .addon-settings__header p { margin: 2.7rem 0 .7rem; color: #155eef; font-size: .7rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .addon-settings h1, .addon-settings h2 { margin: 0; font-family: Georgia, serif; font-weight: 400; letter-spacing: -.045em; }
    .addon-settings h1 { font-size: clamp(2.7rem, 5vw, 4.5rem); line-height: .96; }
    .addon-settings__header span { display: block; max-width: 58ch; margin-top: 1rem; color: #69717c; line-height: 1.6; }
    .addon-notice { margin: 1.5rem 0 0; padding: 1rem; background: #edf4ff; color: #174ea6; font-size: .86rem; }
    .addon-form { margin-top: 3rem; }
    .addon-form__intro { display: flex; align-items: end; justify-content: space-between; gap: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #111; }
    .addon-form h2 { font-size: clamp(2rem, 3vw, 3rem); line-height: 1; }
    .addon-form__intro p { margin: .7rem 0 0; color: #69717c; font-size: .9rem; line-height: 1.55; }
    .addon-button { min-height: 2.75rem; border: 0; padding: .75rem 1rem; background: #155eef; color: #fff; font: 700 .8rem inherit; cursor: pointer; transition: background .2s, transform .16s; }
    .addon-button:hover { background: #1047b7; transform: translateY(-1px); }
    .addon-button:active { transform: translateY(1px); }
    .addon-button:focus-visible, .addon-remove:focus-visible, .addon-form a:focus-visible, .addon-field input:focus-visible, .addon-switch input:focus-visible + span { outline: 2px solid #155eef; outline-offset: 3px; }
    .addon-button--secondary { flex: 0 0 auto; background: #111; }
    .addon-button--secondary:hover { background: #303030; }
    .addon-empty { padding: 3rem 0; border-bottom: 1px solid #dfe2e7; color: #69717c; text-align: center; }
    .addon-empty p { margin: 0; color: #111; font-weight: 700; }
    .addon-empty span { display: block; margin-top: .45rem; font-size: .85rem; }
    .addon-list { border-bottom: 1px solid #dfe2e7; }
    .addon-row { display: grid; grid-template-columns: 2.5rem minmax(0, 1.1fr) minmax(10rem, .65fr); gap: 1rem 1.5rem; align-items: end; padding: 1.5rem 0; border-bottom: 1px solid #dfe2e7; }
    .addon-row:last-child { border-bottom: 0; }
    .addon-row__number { padding-bottom: .85rem; color: #a2a8b0; font-size: .75rem; font-weight: 800; letter-spacing: .08em; }
    .addon-field label { display: block; margin-bottom: .6rem; color: #20242a; font-size: .78rem; font-weight: 750; }
    .addon-field label span { color: #747b85; font-weight: 500; }
    .addon-field input { width: 100%; min-height: 2.75rem; border: 0; border-bottom: 1px solid #bec5ce; background: transparent; border-radius: 0; padding: .55rem 0; color: #111; font: 400 .95rem inherit; outline: 0; transition: border-color .2s, box-shadow .2s; }
    .addon-field input:focus { border-color: #155eef; box-shadow: 0 2px 0 #155eef; }
    .addon-price-input { display: flex; align-items: center; gap: .6rem; border-bottom: 1px solid #bec5ce; }
    .addon-price-input span { color: #68707b; font-size: .85rem; font-weight: 700; }
    .addon-price-input input { border: 0; }
    .addon-price-input:focus-within { border-color: #155eef; box-shadow: 0 2px 0 #155eef; }
    .addon-field em, .addon-error { display: block; margin-top: .55rem; color: #c5221f; font-size: .75rem; font-style: normal; }
    .addon-field--description, .addon-row__actions { grid-column: 2 / -1; }
    .addon-row__actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .addon-switch { display: inline-flex; align-items: center; gap: .65rem; color: #68707b; font-size: .78rem; cursor: pointer; }
    .addon-switch input { position: absolute; opacity: 0; }
    .addon-switch span { width: 2.25rem; height: 1.3rem; border-radius: 999px; background: #aeb6c0; padding: .15rem; transition: background .2s; }
    .addon-switch span::after { display: block; width: .99rem; height: .99rem; border-radius: 50%; background: #fff; content: ''; transition: transform .2s; }
    .addon-switch input:checked + span { background: #155eef; }
    .addon-switch input:checked + span::after { transform: translateX(.95rem); }
    .addon-switch b { min-width: 3.5rem; font-weight: 700; }
    .addon-remove { border: 0; background: transparent; color: #b42318; font: 700 .78rem inherit; cursor: pointer; }
    .addon-form__footer { display: flex; justify-content: flex-end; align-items: center; gap: 1rem; padding-top: 2rem; }
    .addon-form__footer a { color: #626a75; font-size: .82rem; font-weight: 700; text-decoration: none; }
    [x-cloak] { display: none !important; }
    @media (max-width: 640px) { .addon-settings__shell { width: min(100% - 1.5rem, 980px); padding-top: 2rem; } .addon-form__intro { display: block; } .addon-form__intro .addon-button { width: 100%; margin-top: 1.25rem; } .addon-row { grid-template-columns: 2rem 1fr; gap: 1rem; } .addon-field--price { grid-column: 2; } .addon-field--description, .addon-row__actions { grid-column: 2; } .addon-form__footer { justify-content: stretch; } .addon-form__footer a, .addon-form__footer button { flex: 1; text-align: center; } }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('serviceAddonEditor', (initialRows) => ({
            rows: initialRows.map((addon, index) => ({ ...addon, is_active: Boolean(Number(addon.is_active)), key: `existing-${addon.id ?? index}` })),
            nextKey: initialRows.length,
            addRow() {
                this.rows.push({ id: null, name: '', description: '', price: '', is_active: true, key: `new-${this.nextKey++}` });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            },
        }));
    });
</script>
@endpush

@extends('layouts.app')

@section('title', 'Ajukan Jasa - SkillHub')
@section('hideNavigation', true)

@section('content')
    <div class="jasa-ajukan-page bg-bg-soft font-sans text-text antialiased">

        {{-- Top navigation --}}
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <a href="{{ route('dashboard') }}" class="group mt-8 inline-flex items-center gap-2 text-sm font-semibold text-black">
                <span class="transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true">←</span>
                <span class="relative">
                    Dashboard
                    <span class="absolute -bottom-0.5 left-0 h-px w-0 bg-black transition-all duration-200 ease-out group-hover:w-full"></span>
                </span>
            </a>
        </div>

        {{-- Hero --}}
        <header class="mx-auto max-w-6xl px-5 pt-10 pb-14 sm:px-8 lg:grid lg:grid-cols-12 lg:gap-12 lg:pt-14">
            <div class="lg:col-span-7">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-[#0051BA]">Mulai berkarya</p>
                <h1 class="mt-5 font-heading text-5xl font-extrabold leading-[0.95] tracking-[-0.04em] text-black sm:text-6xl lg:text-7xl">
                    Mau ajuin jasa yaw?
                </h1>
                <p class="mt-4 font-heading text-xl font-bold tracking-tight text-black/80 sm:text-2xl">Isi ini dulu gih!</p>
                <p class="mt-6 max-w-md text-base leading-7 text-text-secondary">
                    Ceritakan keahlianmu dengan jelas. Setelah dikirim, admin akan meninjau jasa sebelum tampil di marketplace.
                </p>
            </div>

            <div class="mt-10 lg:col-span-5 lg:mt-0">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-text-secondary">Alur pengajuan</p>
                <ol class="mt-4 border-y border-border">
                    @foreach ([['n' => '01', 't' => 'Pilih bidang keahlianmu', 'd' => 'Tentukan kategori & subkategori jasamu.'], ['n' => '02', 't' => 'Jelaskan jasa yang kamu tawarkan', 'd' => 'Lengkapi nama, harga, dan deskripsi.'], ['n' => '03', 't' => 'Kirim untuk ditinjau admin', 'd' => 'Admin akan memverifikasi sebelum publikasi.']] as $step)
                        <li class="flex items-baseline gap-5 py-5 @if (!$loop->last) border-b border-border @endif">
                            <span class="font-heading text-2xl font-extrabold leading-none text-[#0051BA]">{{ $step['n'] }}</span>
                            <div>
                                <p class="font-heading text-sm font-bold uppercase tracking-wide text-black">{{ $step['t'] }}</p>
                                <p class="mt-1 text-sm leading-6 text-text-secondary">{{ $step['d'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </header>

        {{-- Step indicator --}}
        <nav class="mx-auto max-w-3xl px-5 sm:px-8" aria-label="Langkah pengajuan">
            <ol class="flex items-center gap-3 font-heading text-xs font-bold uppercase tracking-widest text-text-secondary">
                <li class="flex items-center gap-2"><span class="text-[#0051BA]">01</span><span class="hidden sm:inline">Pilih bidang</span></li>
                <li class="h-px flex-1 bg-border" aria-hidden="true"></li>
                <li class="flex items-center gap-2"><span>02</span><span class="hidden sm:inline">Jelaskan jasa</span></li>
                <li class="h-px flex-1 bg-border" aria-hidden="true"></li>
                <li class="flex items-center gap-2"><span>03</span><span class="hidden sm:inline">Kirim</span></li>
            </ol>
        </nav>

        {{-- Form --}}
        <section class="mx-auto max-w-3xl px-5 pb-24 pt-12 sm:px-8">
            <div class="mb-10">
                <p class="font-heading text-xs font-bold uppercase tracking-[0.28em] text-[#0051BA]">Form pengajuan</p>
                <h2 class="mt-3 font-heading text-3xl font-extrabold tracking-tight text-black sm:text-4xl">Kenalin jasamu ke SkillHub</h2>
                <p class="mt-3 max-w-lg text-base leading-7 text-text-secondary">Lengkapi detail berikut agar admin dapat meninjaunya dengan mudah.</p>
            </div>

            <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data" autocomplete="off" class="space-y-12" x-data="{ submitting: false }" @submit="submitting = true">

                @csrf

                @if($errors->any())
                    <div class="border-l-4 border-red-700 bg-red-50 px-4 py-3 text-sm text-red-900" role="alert">
                        <strong>Pengajuan belum terkirim.</strong> {{ $errors->first() }}
                    </div>
                @endif

                {{-- Hidden templates for dynamic fields --}}
                <template id="joki-ml-template">
                    @include('partials.service-types.joki-ml-fields')
                </template>

                <template id="barber-template">
                    @include('partials.service-types.barber-fields')
                </template>

                {{-- 01 Kenalin jasamu --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">01</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Kenalin jasamu</h3>
                    </div>
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="label-field">Nama jasa</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Desain poster acara sekolah" class="input-field @error('title') border-[#0051BA] @enderror">
                            @error('title')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="category_id" class="label-field">Kategori</label>
                                <select name="category_id" id="category_id" required class="input-field @error('category_id') border-[#0051BA] @enderror">
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="subcategory_id" class="label-field">Subkategori</label>
                                <select name="subcategory_id" id="subcategory_id" required disabled class="input-field @error('subcategory_id') border-[#0051BA] @enderror">
                                    <option value="">Pilih kategori dulu</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" @selected(old('subcategory_id') == $subcategory->id)>{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                                @error('subcategory_id')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Request Kategori Toggle --}}
                        <div class="mt-4 p-4 bg-[#FFF9E6] rounded">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-black mb-1">Tidak menemukan kategori yang sesuai?</p>
                                    <p class="text-xs text-[#555555]">Request kategori atau subkategori baru ke admin</p>
                                </div>
                                <button type="button" 
                                        @click="$dispatch('open-category-request-modal')"
                                        class="shrink-0 px-3 py-1.5 text-xs font-bold uppercase tracking-wide bg-[#0051BA] text-white hover:bg-[#003d8f] transition">
                                    Request
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                        {{-- 02 Tentukan nilainya --}}
                        <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                            <div>
                                <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">02</span>
                                <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tentukan nilainya</h3>
                            </div>
                            <div>
                                {{-- Standard price field --}}
                                <div id="standard-price-field">
                                    <label for="price" class="label-field">Harga jasa</label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center font-heading text-sm font-bold text-text-secondary">Rp</span>
                                        <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" inputmode="numeric" placeholder="50000" class="input-field pl-12 tabular-nums @error('price') border-[#0051BA] @enderror">
                                    </div>
                                    @error('price')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                                </div>

                                {{-- Dynamic service type fields container --}}
                                <div id="service-type-fields" style="display: none;"></div>
                            </div>
                        </section>

                {{-- 03 Ceritakan jasamu --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">03</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Ceritakan jasamu</h3>
                    </div>
                    <div>
                        <label for="description" class="label-field">Deskripsi jasa</label>
                        <textarea name="description" id="description" rows="6" required placeholder="Jelaskan apa yang akan kamu kerjakan, hasil yang didapat, dan ketentuan jasamu..." class="input-field resize-y leading-7 @error('description') border-[#0051BA] @enderror">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                    </div>
                </section>

                {{-- 04 Data yang diisi buyer --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]" x-data="buyerFieldBuilder()">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">04</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Data dari buyer</h3>
                        <p class="mt-2 text-sm leading-6 text-text-secondary">Opsional. Tambahkan hanya informasi yang benar-benar diperlukan untuk mengerjakan jasa.</p>
                    </div>
                    <div>
                        <div class="border border-border bg-white p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div><h4 class="font-heading text-sm font-bold text-black">Field pesanan buyer</h4><p class="mt-1 text-xs leading-5 text-text-secondary">Teks, angka, tanggal, atau pilihan. Maksimal 10 field.</p></div>
                                <button type="button" class="btn-ghost shrink-0 px-3 py-2 text-xs" @click="add()" :disabled="fields.length >= 10">+ Tambah field</button>
                            </div>
                            <p class="mt-4 text-sm text-text-secondary" x-show="fields.length === 0">Belum ada field tambahan. Buyer hanya akan melihat catatan umum pesanan.</p>
                            @if($errors->has('buyer_fields.*'))<p class="mt-3 text-xs font-medium text-red-700">Periksa kembali field buyer yang ditandai, terutama pilihan yang membutuhkan minimal dua opsi.</p>@endif
                            <div class="mt-4 space-y-4" x-show="fields.length">
                                <template x-for="(field, index) in fields" :key="field.key">
                                    <fieldset class="border border-border bg-bg-soft p-4">
                                        <legend class="sr-only">Field buyer</legend>
                                        <div class="mb-3 flex items-center justify-between gap-3"><span class="font-heading text-xs font-bold tracking-widest text-[#0051BA]" x-text="String(index + 1).padStart(2, '0')"></span><button type="button" class="text-xs font-bold text-red-700 hover:underline" @click="remove(index)">Hapus</button></div>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <label class="label-field">Label field<input type="text" class="input-field mt-1" :name="`buyer_fields[${index}][label]`" x-model="field.label" maxlength="80" required placeholder="Contoh: Ukuran desain"></label>
                                            <label class="label-field">Jenis input<select class="input-field mt-1" :name="`buyer_fields[${index}][type]`" x-model="field.type"><option value="text">Teks singkat</option><option value="textarea">Teks panjang</option><option value="number">Angka</option><option value="date">Tanggal</option><option value="select">Pilihan</option></select></label>
                                            <label class="label-field sm:col-span-2" x-show="field.type === 'select'">Pilihan <span class="normal-case font-normal text-text-secondary">(pisahkan dengan koma)</span><input type="text" class="input-field mt-1" :name="`buyer_fields[${index}][options]`" x-model="field.options" :required="field.type === 'select'" placeholder="Contoh: Merah, Biru, Hijau"></label>
                                            <label class="label-field"><span class="block min-h-8">Contoh jawaban <span class="block normal-case font-normal tracking-normal text-text-secondary">(opsional)</span></span><input type="text" class="input-field mt-1" :name="`buyer_fields[${index}][placeholder]`" x-model="field.placeholder" maxlength="150" placeholder="Contoh: Ukuran A4"></label>
                                            <label class="label-field"><span class="block min-h-8">Petunjuk <span class="block normal-case font-normal tracking-normal text-text-secondary">(opsional)</span></span><input type="text" class="input-field mt-1" :name="`buyer_fields[${index}][help_text]`" x-model="field.help_text" maxlength="255" placeholder="Jelaskan informasi yang dibutuhkan"></label>
                                        </div>
                                        <label class="mt-3 inline-flex min-h-11 items-center gap-2 text-sm font-medium text-black"><input type="hidden" :name="`buyer_fields[${index}][required]`" value="0"><input type="checkbox" :name="`buyer_fields[${index}][required]`" value="1" x-model="field.required" class="h-4 w-4"> Wajib diisi buyer</label>
                                    </fieldset>
                                </template>
                            </div>
                            <label class="mt-5 flex min-h-11 cursor-pointer items-start gap-3 border-t border-border pt-5 text-sm text-black">
                                <input type="hidden" name="time_slots_enabled" value="0">
                                <input type="checkbox" name="time_slots_enabled" value="1" @checked(old('time_slots_enabled')) class="mt-0.5 h-4 w-4">
                                <span><strong class="block font-heading text-sm">Aktifkan booking slot</strong><span class="mt-1 block text-xs leading-5 text-text-secondary">Buyer wajib memilih tanggal dan waktu tersedia sebelum mengirim pesanan.</span></span>
                            </label>
                        </div>
                    </div>
                </section>

                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]" x-data="addonBuilder()">
                    <div><span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">05</span><h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Layanan tambahan</h3><p class="mt-2 text-sm leading-6 text-text-secondary">Opsional. Tambahkan opsi berbayar yang buyer dapat pilih.</p></div>
                    <div class="border border-border bg-white p-5"><div class="flex items-start justify-between gap-4"><div><h4 class="font-heading text-sm font-bold text-black">Opsi add-on</h4><p class="mt-1 text-xs leading-5 text-text-secondary">Maksimal 20 layanan; admin akan meninjau harga dan keterangannya.</p></div><button type="button" class="btn-ghost shrink-0 px-3 py-2 text-xs" @click="add()" :disabled="addons.length >= 20">+ Tambah layanan</button></div><p class="mt-4 text-sm text-text-secondary" x-show="addons.length === 0">Tidak ada layanan tambahan.</p><div class="mt-4 space-y-3" x-show="addons.length"><template x-for="(addon, index) in addons" :key="addon.key"><fieldset class="border border-border bg-bg-soft p-4"><div class="mb-3 flex justify-between"><span class="font-heading text-xs font-bold text-[#0051BA]" x-text="String(index + 1).padStart(2, '0')"></span><button type="button" @click="remove(index)" class="text-xs font-bold text-red-700 hover:underline">Hapus</button></div><div class="grid gap-3 sm:grid-cols-2"><label class="label-field">Nama layanan<input class="input-field mt-1" type="text" :name="`addons[${index}][name]`" x-model="addon.name" required maxlength="100" placeholder="Contoh: Revisi tambahan"></label><label class="label-field">Harga tambahan (Rp)<input class="input-field mt-1" type="number" :name="`addons[${index}][price]`" x-model="addon.price" required min="0" inputmode="numeric" placeholder="15000"></label><label class="label-field sm:col-span-2">Keterangan <span class="normal-case font-normal text-text-secondary">(opsional)</span><input class="input-field mt-1" type="text" :name="`addons[${index}][description]`" x-model="addon.description" maxlength="255" placeholder="Jelaskan manfaat layanan tambahan"></label></div></fieldset></template></div>@error('addons')<p class="mt-3 text-xs font-medium text-red-700">{{ $message }}</p>@enderror</div>
                </section>

                {{-- 06 Tampilkan karyamu --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">06</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tampilkan karyamu</h3>
                    </div>
                    <div class="space-y-8">

                        {{-- Example image --}}
                        <div x-data="{ img: '', uploadError: '' }">
                            <label for="image" class="label-field">Gambar contoh <span class="font-medium normal-case tracking-normal text-text-secondary">(opsional)</span></label>
                            <label for="image" class="group flex cursor-pointer flex-col items-center justify-center border border-dashed border-border bg-white px-6 py-10 text-center transition duration-200 hover:border-[#0051BA]">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full border border-border text-2xl leading-none text-black transition duration-200 group-hover:border-[#0051BA] group-hover:text-[#0051BA]" aria-hidden="true">+</span>
                                <span class="mt-4 font-heading text-sm font-bold uppercase tracking-wide text-black">Tambahkan gambar contoh</span>
                                <span class="mt-1 text-xs text-text-secondary" x-text="img || 'JPG / PNG / WEBP · Maks. 2 MB'"></span>
                            </label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="const file = $event.target.files[0]; uploadError = file && file.size > 2 * 1024 * 1024 ? 'Ukuran gambar melebihi 2 MB.' : ''; img = uploadError ? '' : (file?.name || ''); if (uploadError) $event.target.value = ''">
                            <p class="mt-2 text-xs text-text-secondary">JPG, PNG, atau WEBP, maksimal 2 MB.</p><p x-show="uploadError" x-text="uploadError" class="mt-2 text-xs font-medium text-red-700"></p>
                            @error('image')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Portfolio --}}
                        <div
                            x-data="{
                                files: [],
                                dragActive: false, uploadError: '',
                                setFiles(list) {
                                    this.files.forEach(item => URL.revokeObjectURL(item.preview));
                                    const candidates = Array.from(list);
                                    const oversized = candidates.find(file => file.size > 2 * 1024 * 1024);
                                    this.uploadError = oversized ? `“${oversized.name}” melebihi batas 2 MB.` : '';
                                    this.files = candidates.filter(file => file.size <= 2 * 1024 * 1024).slice(0, 3).map(file => ({ file, preview: URL.createObjectURL(file) }));
                                    const transfer = new DataTransfer();
                                    this.files.forEach(item => transfer.items.add(item.file));
                                    this.$refs.portfolioInput.files = transfer.files;
                                },
                                removeFile(index) {
                                    URL.revokeObjectURL(this.files[index].preview);
                                    this.files.splice(index, 1);
                                    const transfer = new DataTransfer();
                                    this.files.forEach(item => transfer.items.add(item.file));
                                    this.$refs.portfolioInput.files = transfer.files;
                                }
                            }"
                            @dragover.prevent="dragActive = true"
                            @dragleave.prevent="dragActive = false"
                            @drop.prevent="dragActive = false; setFiles($event.dataTransfer.files)"
                        >
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label for="portfolio_images" class="label-field mb-0">Portofolio <span class="font-medium normal-case tracking-normal text-text-secondary">(maks. 3 foto)</span></label>
                                <span class="font-heading text-xs font-bold uppercase tracking-widest text-[#0051BA]" x-text="files.length + ' / 3 foto'"></span>
                            </div>
                            <label for="portfolio_images" class="group flex cursor-pointer flex-col items-center justify-center border border-dashed px-5 py-8 text-center transition duration-200" :class="dragActive ? 'border-[#0051BA] bg-[#0051BA]/5' : 'border-border bg-white hover:border-[#0051BA]'">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full border border-border text-xl text-black transition duration-200 group-hover:border-[#0051BA]" aria-hidden="true">↑</span>
                                <span class="mt-3 font-heading text-sm font-bold uppercase tracking-wide text-black">Tarik foto ke sini atau pilih dari perangkat</span>
                                <span class="mt-1 text-xs text-text-secondary">JPG, PNG, atau WEBP · maksimal 2 MB per foto</span>
                                <input x-ref="portfolioInput" @change="setFiles($event.target.files)" type="file" name="portfolio_images[]" id="portfolio_images" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
                            </label>
                            <div x-show="files.length" x-cloak class="mt-3 grid grid-cols-3 gap-3">
                                <template x-for="(item, index) in files" :key="item.preview">
                                    <div class="group relative aspect-square overflow-hidden border border-border bg-bg-muted">
                                        <img :src="item.preview" alt="Pratinjau portofolio" class="h-full w-full object-cover">
                                        <button type="button" @click="removeFile(index)" class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-black/75 text-sm font-bold text-white opacity-0 transition group-hover:opacity-100 focus:opacity-100" aria-label="Hapus foto">×</button>
                                    </div>
                                </template>
                            </div>
                            @error('portfolio_images')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                            @error('portfolio_images.*')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                            <p x-show="uploadError" x-text="uploadError" class="mt-2 text-xs font-medium text-red-700"></p>
                        </div>

                    </div>
                </section>

                {{-- Actions --}}
                <div class="flex flex-col-reverse gap-3 border-t border-border pt-8 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('dashboard') }}" class="btn-ghost justify-center px-4 py-3 sm:justify-start">Nanti dulu</a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 bg-[#0051BA] text-white font-heading font-bold text-xs uppercase tracking-wider px-6 py-3 border-2 border-[#0051BA] transition-all duration-150 hover:bg-white hover:text-[#0051BA] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed" :disabled="submitting">
                        <span x-show="!submitting">Kirim untuk ditinjau <span aria-hidden="true">→</span></span>
                        <span x-show="submitting" x-cloak>Mengirim…</span>
                    </button>
                </div>
            </form>
        </section>

        {{-- Service type dynamic data --}}
        <div x-data="{
            subcategoryId: null,
            serviceType: null,
            loading: false,
            
            async loadServiceType(subcategoryId) {
                if (!subcategoryId) {
                    this.serviceType = null;
                    this.hideStandardPriceField = false;
                    return;
                }
                
                this.loading = true;
                
                try {
                    const response = await fetch(`/api/subcategories/${subcategoryId}/service-type`);
                    const data = await response.json();
                    this.serviceType = data.service_type;
                    
                    // Hide/show standard price field based on service type
                    if (this.serviceType?.has_custom_pricing) {
                        document.getElementById('standard-price-field').style.display = 'none';
                        // Dynamically load service type specific fields
                        this.loadServiceTypeFields();
                    } else {
                        document.getElementById('standard-price-field').style.display = 'block';
                        document.getElementById('service-type-fields').innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error loading service type:', error);
                } finally {
                    this.loading = false;
                }
            },
            
            async loadServiceTypeFields() {
                if (!this.serviceType) return;
                
                const container = document.getElementById('service-type-fields');
                
                switch (this.serviceType.code) {
                    case 'joki_ml':
                        const jokiResponse = await fetch('/partials/service-types/joki-ml-fields');
                        container.innerHTML = await jokiResponse.text();
                        break;
                    case 'barber':
                        const barberResponse = await fetch('/partials/service-types/barber-fields');
                        container.innerHTML = await barberResponse.text();
                        break;
                    default:
                        container.innerHTML = '';
                }
            }
        }" x-init="() => {
            const subcategorySelect = document.getElementById('subcategory_id');
            if (subcategorySelect) {
                subcategorySelect.addEventListener('change', (e) => {
                    this.subcategoryId = e.target.value;
                    this.loadServiceType(e.target.value);
                });
            }
        }"></div>

        {{-- Modal Request Kategori (Di luar form utama) --}}
        <div x-data="{ showModal: false }" 
             @open-category-request-modal.window="showModal = true"
             @keydown.escape.window="showModal = false"
             x-show="showModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showModal = false"></div>
            
            {{-- Modal Content --}}
            <div class="flex min-h-screen items-center justify-center p-4">
                <div @click.away="showModal = false" 
                     class="relative w-full max-w-lg bg-white shadow-xl transform transition-all">
                    
                    {{-- Header --}}
                    <div class="border-b border-[#DDDDDD] px-6 py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-heading text-lg font-bold text-black">Request Kategori/Subkategori</h3>
                            <button type="button" @click="showModal = false" class="text-2xl leading-none text-[#999999] hover:text-black transition">&times;</button>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('category-request.store') }}" class="p-6 space-y-4">
                        @csrf

                        {{-- Request Type --}}
                        <div x-data="{ requestType: '' }">
                            <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Tipe Request <span class="text-[#E4002B]">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label class="relative flex items-center px-3 py-2.5 border cursor-pointer transition text-xs"
                                       :class="requestType === 'category_request' ? 'border-[#0051BA] bg-[#0051BA] text-white' : 'border-[#DDDDDD] hover:border-[#0051BA]'">
                                    <input type="radio" name="request_type" value="category_request" x-model="requestType" required class="sr-only">
                                    <span class="font-bold uppercase">Kategori Baru</span>
                                </label>
                                <label class="relative flex items-center px-3 py-2.5 border cursor-pointer transition text-xs"
                                       :class="requestType === 'subcategory_request' ? 'border-[#0051BA] bg-[#0051BA] text-white' : 'border-[#DDDDDD] hover:border-[#0051BA]'">
                                    <input type="radio" name="request_type" value="subcategory_request" x-model="requestType" required class="sr-only">
                                    <span class="font-bold uppercase">Subkategori Baru</span>
                                </label>
                            </div>

                            {{-- Category Request Fields --}}
                            <div x-show="requestType === 'category_request'" x-collapse class="mt-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Nama Kategori <span class="text-[#E4002B]">*</span></label>
                                    <input type="text" 
                                           name="requested_category_name" 
                                           :required="requestType === 'category_request'"
                                           class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm"
                                           placeholder="Contoh: Fotografi & Videografi">
                                </div>
                            </div>

                            {{-- Subcategory Request Fields --}}
                            <div x-show="requestType === 'subcategory_request'" x-collapse class="mt-4 space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Pilih Kategori <span class="text-[#E4002B]">*</span></label>
                                    <select name="existing_category_id" 
                                            :required="requestType === 'subcategory_request'"
                                            class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Nama Subkategori <span class="text-[#E4002B]">*</span></label>
                                    <input type="text" 
                                           name="requested_subcategory_name" 
                                           :required="requestType === 'subcategory_request'"
                                           class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm"
                                           placeholder="Contoh: Wedding Photography">
                                </div>
                            </div>

                            {{-- Reason --}}
                            <div class="mt-4">
                                <label class="block text-xs font-bold uppercase tracking-wide text-black mb-2">Alasan Request <span class="text-[#E4002B]">*</span></label>
                                <textarea name="reason_for_request" 
                                          rows="3" 
                                          required
                                          minlength="10"
                                          maxlength="1000"
                                          class="w-full px-3 py-2 border border-[#DDDDDD] focus:border-[#0051BA] focus:outline-none transition text-sm resize-y"
                                          placeholder="Jelaskan mengapa kategori/subkategori ini perlu ditambahkan..."></textarea>
                                <p class="text-xs text-[#999999] mt-1">Min 10 karakter, max 1000 karakter</p>
                            </div>

                            {{-- Submit --}}
                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" 
                                        @click="showModal = false"
                                        class="px-4 py-2 text-xs font-bold uppercase border border-[#DDDDDD] hover:border-[#555555] transition">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 text-xs font-bold uppercase bg-[#0051BA] text-white hover:bg-black transition">
                                    Kirim Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Dynamic service type form data (Alpine.js) --}}
    <div x-data="{
        subcategoryId: null,
        serviceType: null,
        
        async loadServiceType(subcategoryId) {
            if (!subcategoryId) {
                this.serviceType = null;
                document.getElementById('standard-price-field').style.display = 'block';
                document.getElementById('service-type-fields').innerHTML = '';
                return;
            }
            
            try {
                const response = await fetch(`/api/subcategories/${subcategoryId}/service-type`);
                const data = await response.json();
                this.serviceType = data.service_type;
                
                if (this.serviceType?.has_custom_pricing) {
                    document.getElementById('standard-price-field').style.display = 'none';
                    this.loadServiceTypeFields();
                } else {
                    document.getElementById('standard-price-field').style.display = 'block';
                    document.getElementById('service-type-fields').innerHTML = '';
                }
            } catch (error) {
                console.error('Error loading service type:', error);
            }
        },
        
        loadServiceTypeFields() {
            if (!this.serviceType) return;
            
            const container = document.getElementById('service-type-fields');
            
            switch (this.serviceType.code) {
                case 'joki_ml':
                    container.innerHTML = document.querySelector('[data-service-type-joki-ml]')?.outerHTML || '';
                    break;
                case 'barber':
                    container.innerHTML = document.querySelector('[data-service-type-barber]')?.outerHTML || '';
                    break;
                default:
                    container.innerHTML = '';
            }
        }
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const defaultOption = subcategorySelect.querySelector('option[value=""]');
            const standardPriceField = document.getElementById('standard-price-field');
            const serviceTypeFields = document.getElementById('service-type-fields');
            const priceInput = document.getElementById('price');

            const refreshSubcategories = () => {
                const selectedCategoryId = categorySelect.value;

                Array.from(subcategorySelect.options).forEach((option) => {
                    if (!option.value) return;
                    option.hidden = option.dataset.categoryId !== selectedCategoryId;
                });

                subcategorySelect.disabled = !selectedCategoryId;
                defaultOption.textContent = selectedCategoryId ? 'Pilih subkategori' : 'Pilih kategori dulu';

                const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex];
                if (selectedOption && selectedOption.hidden) subcategorySelect.value = '';
                
                // Reset dynamic fields when category changes
                loadDynamicFields('');
            };

            const loadDynamicFields = async (subcategoryId) => {
                if (!subcategoryId) {
                    serviceTypeFields.innerHTML = '';
                    serviceTypeFields.style.display = 'none';
                    standardPriceField.style.display = 'block';
                    priceInput.required = true;
                    return;
                }

                try {
                    const response = await fetch(`/api/subcategories/${subcategoryId}/service-type`);
                    const data = await response.json();
                    const serviceType = data.service_type;

                    const hasTypeFields = ['joki_ml', 'barber'].includes(serviceType?.code);
                    const customPricing = Boolean(serviceType?.has_custom_pricing);
                    standardPriceField.style.display = customPricing ? 'none' : 'block';
                    priceInput.required = !customPricing;
                    serviceTypeFields.style.display = hasTypeFields ? 'block' : 'none';

                    if (hasTypeFields) {
                        if (serviceType.code === 'joki_ml') {
                            const template = document.getElementById('joki-ml-template');
                            serviceTypeFields.innerHTML = template.innerHTML;
                        } else if (serviceType.code === 'barber') {
                            const template = document.getElementById('barber-template');
                            serviceTypeFields.innerHTML = template.innerHTML;
                        }
                    } else {
                        serviceTypeFields.innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error loading service type:', error);
                    serviceTypeFields.innerHTML = '';
                    serviceTypeFields.style.display = 'none';
                    standardPriceField.style.display = 'block';
                    priceInput.required = true;
                }
            };

            categorySelect.addEventListener('change', () => {
                subcategorySelect.value = '';
                refreshSubcategories();
            });

            subcategorySelect.addEventListener('change', () => {
                loadDynamicFields(subcategorySelect.value);
            });

            // Initialize on page load if category/subcategory already selected
            refreshSubcategories();
            if (subcategorySelect.value) {
                loadDynamicFields(subcategorySelect.value);
            }
        });

        function buyerFieldBuilder() {
            return {
                fields: @js(old('buyer_fields', [])).map((field, index) => ({ ...field, key: index, required: Boolean(Number(field.required)) })),
                nextKey: @js(count(old('buyer_fields', []))),
                add() { if (this.fields.length < 10) this.fields.push({ key: this.nextKey++, label: '', type: 'text', options: '', placeholder: '', help_text: '', required: false }); },
                remove(index) { this.fields.splice(index, 1); },
            };
        }
        function addonBuilder() {
            return { addons: @js(old('addons', [])).map((addon, index) => ({ ...addon, key: index })), nextKey: @js(count(old('addons', []))), add() { if (this.addons.length < 20) this.addons.push({ key: this.nextKey++, name: '', price: '', description: '' }); }, remove(index) { this.addons.splice(index, 1); } };
        }
    </script>
@endpush

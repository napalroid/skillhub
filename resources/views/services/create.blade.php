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
                    </div>
                </section>

                {{-- 02 Tentukan nilainya --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">02</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tentukan nilainya</h3>
                    </div>
                    <div>
                        <label for="price" class="label-field">Harga jasa</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center font-heading text-sm font-bold text-text-secondary">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" inputmode="numeric" placeholder="50000" class="input-field pl-12 tabular-nums @error('price') border-[#0051BA] @enderror">
                        </div>
                        @error('price')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
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

                {{-- 04 Tampilkan karyamu --}}
                <section class="grid gap-6 border-t border-border pt-8 lg:grid-cols-[200px_1fr]">
                    <div>
                        <span class="font-heading text-sm font-extrabold tracking-widest text-[#0051BA]">04</span>
                        <h3 class="mt-1 font-heading text-lg font-bold tracking-tight text-black">Tampilkan karyamu</h3>
                    </div>
                    <div class="space-y-8">

                        {{-- Example image --}}
                        <div x-data="{ img: '' }">
                            <label for="image" class="label-field">Gambar contoh <span class="font-medium normal-case tracking-normal text-text-secondary">(opsional)</span></label>
                            <label for="image" class="group flex cursor-pointer flex-col items-center justify-center border border-dashed border-border bg-white px-6 py-10 text-center transition duration-200 hover:border-[#0051BA]">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full border border-border text-2xl leading-none text-black transition duration-200 group-hover:border-[#0051BA] group-hover:text-[#0051BA]" aria-hidden="true">+</span>
                                <span class="mt-4 font-heading text-sm font-bold uppercase tracking-wide text-black">Tambahkan gambar contoh</span>
                                <span class="mt-1 text-xs text-text-secondary" x-text="img || 'JPG / PNG · Maks. 2 MB'"></span>
                            </label>
                            <input id="image" name="image" type="file" accept="image/jpeg,image/png" class="sr-only" @change="img = $event.target.files[0]?.name || ''">
                            <p class="mt-2 text-xs text-text-secondary">JPG atau PNG, maksimal 2 MB.</p>
                            @error('image')<p class="mt-2 text-xs font-medium text-[#0051BA]">{{ $message }}</p>@enderror
                        </div>

                        {{-- Portfolio --}}
                        <div
                            x-data="{
                                files: [],
                                dragActive: false,
                                setFiles(list) {
                                    this.files.forEach(item => URL.revokeObjectURL(item.preview));
                                    this.files = Array.from(list).slice(0, 3).map(file => ({ file, preview: URL.createObjectURL(file) }));
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
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const defaultOption = subcategorySelect.querySelector('option[value=""]');

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
            };

            categorySelect.addEventListener('change', () => {
                subcategorySelect.value = '';
                refreshSubcategories();
            });

            refreshSubcategories();
        });
    </script>
@endpush

@section('pageFooter')
    <x-site-footer />
@endsection

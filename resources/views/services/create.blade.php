@extends('layouts.app')

@section('title', 'Ajukan Jasa - SkillHub')
@section('hideNavigation', true)

@section('content')
    <section class="relative isolate overflow-hidden bg-gradient-to-br from-sky-50 via-indigo-50 to-amber-50 px-4 py-12 sm:px-6 sm:py-16">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="absolute -left-20 top-12 h-64 w-64 rounded-full bg-blue-300/45 blur-3xl"></div>
            <div class="absolute -right-20 top-1/4 h-72 w-72 rounded-full bg-violet-300/35 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-amber-200/50 blur-3xl"></div>
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(#2563eb 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        <div class="mx-auto max-w-5xl">
            <div class="grid overflow-hidden rounded-[2rem] border border-white/80 bg-white/80 shadow-2xl shadow-blue-950/10 backdrop-blur-sm lg:grid-cols-[0.85fr_1.15fr]">
                <aside class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 px-7 py-10 text-white sm:px-10 lg:py-12">
                    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full border-[18px] border-white/10"></div>
                    <div class="absolute -bottom-20 -left-16 h-52 w-52 rounded-full bg-amber-300/20"></div>

                    <div class="relative">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-100 transition hover:text-white">
                            <span aria-hidden="true">←</span> Kembali ke dashboard
                        </a>

                        <span class="mt-12 inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-blue-50">Mulai berkarya</span>
                        <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">Mau ajuin jasa yaw?</h1>
                        <p class="mt-3 text-lg font-medium text-blue-100">Isi ini dulu gih!</p>
                        <p class="mt-6 max-w-sm text-sm leading-7 text-blue-100/90">Ceritakan keahlianmu dengan jelas. Setelah dikirim, admin akan meninjau jasa sebelum tampil di marketplace.</p>

                        <div class="mt-10 space-y-4">
                            @foreach ([['number' => '01', 'text' => 'Pilih bidang keahlianmu'], ['number' => '02', 'text' => 'Jelaskan jasa yang kamu tawarkan'], ['number' => '03', 'text' => 'Kirim untuk ditinjau admin']] as $step)
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-xs font-extrabold">{{ $step['number'] }}</span>
                                    <span class="text-sm font-medium text-blue-50">{{ $step['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <div class="p-6 sm:p-10">
                    <div class="mb-8">
                        <p class="text-sm font-bold uppercase tracking-widest text-blue-600">Form pengajuan</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Kenalin jasamu ke SkillHub</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Lengkapi detail berikut agar admin dapat meninjaunya dengan mudah.</p>
                    </div>

                    <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label for="title" class="mb-2 block text-sm font-bold text-slate-700">Nama jasa</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Desain poster acara sekolah" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            @error('title')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="category_id" class="mb-2 block text-sm font-bold text-slate-700">Kategori</label>
                                <select name="category_id" id="category_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="">Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="subcategory_id" class="mb-2 block text-sm font-bold text-slate-700">Subkategori</label>
                                <select name="subcategory_id" id="subcategory_id" required disabled class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition disabled:cursor-not-allowed disabled:opacity-60 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <option value="">Pilih kategori dulu</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}" data-category-id="{{ $subcategory->category_id }}" @selected(old('subcategory_id') == $subcategory->id)>{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                                @error('subcategory_id')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="price" class="mb-2 block text-sm font-bold text-slate-700">Harga jasa</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-sm font-bold text-slate-400">Rp</span>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" inputmode="numeric" placeholder="50000" class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">
                            </div>
                            @error('price')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-bold text-slate-700">Deskripsi jasa</label>
                            <textarea name="description" id="description" rows="5" required placeholder="Jelaskan apa yang akan kamu kerjakan, hasil yang didapat, dan ketentuan jasamu..." class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">{{ old('description') }}</textarea>
                            @error('description')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="image" class="mb-2 block text-sm font-bold text-slate-700">Gambar contoh <span class="font-medium text-slate-400">(opsional)</span></label>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png" class="block w-full cursor-pointer rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-blue-700 hover:file:bg-blue-200">
                            <p class="mt-2 text-xs text-slate-400">JPG atau PNG, maksimal 2 MB.</p>
                            @error('image')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

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
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="portfolio_images" class="block text-sm font-bold text-slate-700">Portofolio <span class="font-medium text-slate-400">(maks. 3 foto)</span></label>
                                <span class="text-xs font-semibold text-blue-600" x-text="files.length + ' / 3 foto'"></span>
                            </div>
                            <label for="portfolio_images" class="flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed px-5 py-7 text-center transition" :class="dragActive ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-slate-50 hover:border-blue-300 hover:bg-blue-50/50'">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-xl text-blue-600 shadow-sm">↑</span>
                                <span class="mt-3 text-sm font-bold text-slate-700">Tarik foto ke sini atau pilih dari perangkat</span>
                                <span class="mt-1 text-xs text-slate-400">JPG, PNG, atau WEBP · maksimal 2 MB per foto</span>
                                <input x-ref="portfolioInput" @change="setFiles($event.target.files)" type="file" name="portfolio_images[]" id="portfolio_images" accept="image/jpeg,image/png,image/webp" multiple class="sr-only">
                            </label>
                            <div x-show="files.length" x-cloak class="mt-3 grid grid-cols-3 gap-3">
                                <template x-for="(item, index) in files" :key="item.preview">
                                    <div class="group relative aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                        <img :src="item.preview" alt="Pratinjau portofolio" class="h-full w-full object-cover">
                                        <button type="button" @click="removeFile(index)" class="absolute right-1.5 top-1.5 flex h-7 w-7 items-center justify-center rounded-full bg-slate-950/75 text-sm font-bold text-white opacity-0 transition group-hover:opacity-100" aria-label="Hapus foto">×</button>
                                    </div>
                                </template>
                            </div>
                            @error('portfolio_images')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            @error('portfolio_images.*')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('dashboard') }}" class="rounded-xl px-4 py-3 text-center text-sm font-bold text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">Nanti dulu</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/30">
                                Kirim untuk ditinjau <span aria-hidden="true">→</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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

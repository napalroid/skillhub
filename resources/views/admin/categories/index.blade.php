<x-layouts.admin>
    @php
        $initialCategories = $categories->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'image_url' => $cat->image ? asset('storage/' . $cat->image) : null,
                'icon_url' => $cat->icon ? asset('storage/' . $cat->icon) : null,
                'icon_is_file' => $cat->iconIsFile(),
                'display_icon' => $cat->displayIcon(),
                'subcategories_count' => $cat->subcategories_count,
                'services_count' => $cat->services_count,
                'subcategories' => $cat->subcategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'services_count' => $sub->services_count,
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    @endphp

    {{-- HERO --}}
    <section class="border border-[#DDDDDD] bg-white px-6 py-8 sm:px-8 sm:py-10 mb-8" data-stagger-item>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-[#999999]">SkillHub Admin</p>
                <h1 class="mt-2 font-heading text-3xl font-bold uppercase leading-[0.95] tracking-tight text-black sm:text-4xl lg:text-5xl">
                    CRUD Kategori &amp; Subkategori
                </h1>
                <p class="mt-3 text-sm leading-relaxed text-[#555555] sm:text-base">
                    Kelola kategori dan struktur subkategori SkillHub dari satu tempat.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button @click="showSubCreateModal = true" class="inline-flex items-center gap-2 border border-[#DDDDDD] bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-black transition-colors duration-150 hover:border-black hover:bg-black hover:text-white font-heading">
                    <span class="inline-flex h-4 w-4 items-center justify-center text-base leading-none">+</span> Tambah Subkategori
                </button>
                <button @click="showCreateModal = true" class="inline-flex items-center gap-2 bg-black px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white transition-colors duration-150 hover:bg-black/80 font-heading">
                    <span class="inline-flex h-4 w-4 items-center justify-center text-base leading-none">+</span> Tambah Kategori
                </button>
            </div>
        </div>
    </section>

    @php
        $categoriesForSelect = $categories->map(function ($cat) {
            return ['id' => $cat->id, 'name' => $cat->name];
        })->values()->all();
    @endphp

    {{-- CREATE SUBKATEGORI MODAL --}}
    <div x-show="showSubCreateModal" x-cloak @click.outside="showSubCreateModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.stop class="w-full max-w-md bg-white rounded-md border border-[#DDDDDD] p-6 shadow-lg"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-heading font-semibold text-sm text-black">Tambah Subkategori</h3>
                <button type="button" @click="showSubCreateModal = false" class="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.subcategories.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="label-field" for="sub-name">Nama Subkategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <input type="text" id="sub-name" name="name" required class="input-field" placeholder="Contoh: Desain Logo">
                    </div>
                    <div>
                        <label class="label-field" for="sub-category">Kategori <span class="text-[#E4002B] font-normal">*</span></label>
                        <select id="sub-category" name="category_id" required class="input-field">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categoriesForSelect as $cat)
                                <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-[#DDDDDD]">
                        <button type="button" @click="showSubCreateModal = false" class="btn-outline text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2">Tambah Subkategori</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- CATEGORY GRID --}}
    <div id="category-grid-root"
         data-props='{{ json_encode([
             "categories" => $initialCategories,
             "loading" => false,
             "categoryFetchUrl" => route("admin.categories.data"),
         ]) }}'
         class="w-full">
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" aria-live="polite" aria-busy="true">
            {{-- Content will be hydrated by React --}}
        </section>
    </div>

    {{-- CREATE CATEGORY MODAL --}}
    <div x-show="showCreateModal" x-cloak @click.outside="showCreateModal = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div @click.stop class="w-full max-w-2xl bg-white rounded-md border border-[#DDDDDD] p-6 shadow-lg max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-heading font-semibold text-sm text-black">Tambah Kategori Baru</h3>
                <button type="button" @click="showCreateModal = false" class="btn-ghost p-1" aria-label="Tutup modal">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                {{-- Nama Kategori --}}
                <div>
                    <label class="label-field" for="cat-name">Nama Kategori <span class="text-[#E4002B] font-normal">*</span></label>
                    <input type="text" id="cat-name" name="name" required
                        class="input-field"
                        placeholder="Contoh: Desain & Grafis">
                    @error('name') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                {{-- Gambar Marketplace (Hero) --}}
                <div>
                    <label class="label-field" for="cat-image">Gambar Marketplace (Hero Section) <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Gambar ini akan tampil di hero section marketplace saat kursor mengarah ke kategori ini. Rasio 16:9 disarankan.</p>
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border-2 border-dashed border-[#DDDDDD] px-4 py-4 hover:border-black hover:bg-[#F5F5F5] transition"
                           x-data="{ dragActive: false }"
                           @dragover.prevent="dragActive = true"
                           @dragleave="dragActive = false"
                           @drop.prevent="dragActive = false; $refs.imageInput.files = $event.dataTransfer.files; imageName = $event.dataTransfer.files[0]?.name || ''"
                           :class="{ 'border-black bg-[#F5F5F5]': dragActive }">
                        <span class="text-2xl">🖼</span>
                        <span class="min-w-0 flex-1 truncate text-sm text-[#555555]" x-text="imageName || 'Klik atau drag gambar ke sini'"></span>
                        <span class="text-xs font-bold text-black shrink-0">PILIH</span>
                        <input type="file" x-ref="imageInput" name="image" accept="image/png,image/jpeg,image/webp" class="hidden"
                            @change="imageName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-1 text-xs text-[#999999]">PNG, JPG, atau WEBP. Maksimal 2 MB.</p>
                    @error('image') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                {{-- Icon Kategori --}}
                <div>
                    <label class="label-field" for="cat-icon">Icon Kategori <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Icon kecil untuk daftar kategori. Bisa upload file atau pakai emoji default sistem.</p>
                    <label class="flex cursor-pointer items-center gap-3 rounded-md border-2 border-dashed border-[#DDDDDD] px-4 py-4 hover:border-black hover:bg-[#F5F5F5] transition"
                           x-data="{ dragActive: false }"
                           @dragover.prevent="dragActive = true"
                           @dragleave="dragActive = false"
                           @drop.prevent="dragActive = false; $refs.iconInput.files = $event.dataTransfer.files; iconName = $event.dataTransfer.files[0]?.name || ''"
                           :class="{ 'border-black bg-[#F5F5F5]': dragActive }">
                        <span class="text-2xl">✨</span>
                        <span class="min-w-0 flex-1 truncate text-sm text-[#555555]" x-text="iconName || 'Klik untuk pilih file icon'"></span>
                        <span class="text-xs font-bold text-black shrink-0">PILIH</span>
                        <input type="file" x-ref="iconInput" name="icon" accept="image/png,image/jpeg,image/webp" class="hidden"
                            @change="iconName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-1 text-xs text-[#999999]">PNG, JPG, atau WEBP. Maksimal 2 MB.</p>
                    @error('icon') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                {{-- Subkategori (comma separated) --}}
                <div>
                    <label class="label-field" for="cat-subs">Subkategori (pisahkan dengan koma) <span class="text-[#999999] font-normal">(opsional)</span></label>
                    <p class="text-xs text-[#999999] mb-2">Contoh: Desain Logo, Desain Poster, Desain Sosial Media, Ilustrasi</p>
                    <textarea id="cat-subs" name="subcategories" rows="3"
                        class="input-field resize-none"
                        placeholder="Desain Logo, Desain Poster, Desain Sosial Media, Ilustrasi"></textarea>
                    @error('subcategories') <p class="mt-1 text-xs text-[#E4002B]">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-2 pt-4 border-t border-[#DDDDDD]">
                    <button type="button" @click="showCreateModal = false" class="btn-outline text-xs px-4 py-2">Batal</button>
                    <button type="submit" class="btn-primary text-xs px-4 py-2">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Bridge: React empty-state CTA -> Alpine modal
        window.addEventListener('skillhub:open-category-modal', function() {
            const root = document.querySelector('[x-data]');
            if (!root || !window.Alpine) return;
            const data = window.Alpine.$data(root);
            if (data && typeof data.showCreateModal !== 'undefined') {
                data.showCreateModal = true;
            }
        });
    });
    </script>
    @endsection
</x-layouts.admin>
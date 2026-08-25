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

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8" data-stagger-item>
        <div>
            <h1 class="font-heading font-bold text-2xl text-black uppercase tracking-tight">Kelola Kategori</h1>
            <p class="text-sm text-[#555555] mt-1">Tambah, edit, hapus kategori beserta subkategori dan gambar/icon marketplace</p>
        </div>
        <button @click="showCreateModal = true" class="btn-primary text-xs">
            <span class="inline-flex items-center justify-center w-5 h-5">+</span> Tambah Kategori
        </button>
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
        
        // Page transition handled by layout
        // React component will handle animations
    });
    </script>
    @endsection
</x-layouts.admin>
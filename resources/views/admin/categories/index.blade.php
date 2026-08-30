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
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" aria-live="polite">
            @foreach($categories as $category)
                <div class="border border-[#DDDDDD] hover:border-black transition-colors p-2.5 sm:p-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                            <div class="flex h-8 w-8 sm:h-9 sm:w-9 shrink-0 items-center justify-center bg-[#F5F5F5] border border-[#DDDDDD]">
                                @if ($category->iconIsFile())
                                    <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }}" class="h-4 w-4 sm:h-5 sm:w-5 object-contain">
                                @elseif ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-4 w-4 sm:h-5 sm:w-5 object-contain">
                                @else
                                    @php $iconKey = $category->displayIcon(); @endphp
                                    @switch($iconKey)
                                        @case('design')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                            @break
                                        @case('code')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"/></svg>
                                            @break
                                        @case('camera')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg>
                                            @break
                                        @case('music')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10.5-3m0 6.553v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66a2.25 2.25 0 0 0 1.632-2.163Zm0 0V2.25L9 5.25v10.303m0 0v3.75a2.25 2.25 0 0 1-1.632 2.163l-1.32.377a1.803 1.803 0 1 1-.99-3.467l2.31-.66A2.25 2.25 0 0 0 9 15.553Z"/></svg>
                                            @break
                                        @case('write')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.549 2.799a2.121 2.121 0 1 1 3 3L19.862 7.487M16.862 4.487 6.348 14.998a2.25 2.25 0 0 0-.578.978l-1.226 4.273a.375.375 0 0 0 .464.464l4.273-1.226a2.25 2.25 0 0 0 .978-.578L18.862 7.487M16.862 4.487 18.549 2.799"/></svg>
                                            @break
                                        @case('learn')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                            @break
                                        @case('business')
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/></svg>
                                            @break
                                        @default
                                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                                    @endswitch
                                @endif
                            </div>
                            <span class="font-medium text-sm text-black truncate">{{ $category->name }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                            <span class="badge badge-neutral text-[9px] sm:text-[10px] px-2 py-0.5">{{ $category->subcategories->count() }}</span>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-ghost p-1" aria-label="Edit kategori">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                            </a>
                        </div>
                    </div>
                    @if ($category->subcategories->isNotEmpty())
                        <div class="mt-2 flex flex-wrap gap-1.5 pl-9 sm:pl-12">
                            @foreach ($category->subcategories->take(6) as $sub)
                                <span class="text-[9px] sm:text-[10px] px-2 py-0.5 rounded border border-[#DDDDDD] bg-white text-[#555555]">{{ $sub->name }}</span>
                            @endforeach
                            @if ($category->subcategories->count() > 6)
                                <span class="text-[9px] sm:text-[10px] px-2 py-0.5 text-[#999999]">+{{ $category->subcategories->count() - 6 }} lagi</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
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
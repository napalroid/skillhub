@extends('layouts.app')

@section('title', 'Tambah Kategori - Admin')

@section('content')
<div class="max-w-5xl mx-auto py-4" x-data="{ mode: '{{ old('mode', 'category') }}', iconName: '', imageName: '' }">
    <div class="mb-8">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-blue-600 mb-4">← Kembali ke kategori</a>
        <p class="text-sm font-semibold tracking-wider text-blue-600 uppercase">Manajemen jasa</p>
        <h1 class="mt-1 text-3xl font-bold text-slate-900">Tambah kategori atau subkategori</h1>
        <p class="mt-2 text-slate-500">Pilih kebutuhan Anda, lalu lengkapi informasi yang diperlukan.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 mb-7">
        <button type="button" @click="mode = 'category'" :class="mode === 'category' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100' : 'border-slate-200 bg-white hover:border-blue-300'" class="text-left rounded-2xl border p-5 transition">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-xl text-white">✦</span>
            <span class="mt-4 block font-semibold text-slate-900">Kategori baru</span>
            <span class="mt-1 block text-sm leading-6 text-slate-500">Buat kategori jasa baru dan tambahkan gambar sebagai identitasnya.</span>
        </button>
        <button type="button" @click="mode = 'subcategory'" :class="mode === 'subcategory' ? 'border-violet-600 bg-violet-50 ring-2 ring-violet-100' : 'border-slate-200 bg-white hover:border-violet-300'" class="text-left rounded-2xl border p-5 transition">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-600 text-xl text-white">⌘</span>
            <span class="mt-4 block font-semibold text-slate-900">Subkategori</span>
            <span class="mt-1 block text-sm leading-6 text-slate-500">Tambahkan detail jasa ke salah satu kategori yang sudah tersedia.</span>
        </button>
    </div>

    <div x-show="mode === 'category'" x-cloak class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf
            <input type="hidden" name="mode" value="category">
            <div class="flex items-center gap-3 mb-7"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600">1</span><div><h2 class="font-semibold text-slate-900">Informasi kategori baru</h2><p class="text-sm text-slate-500">Gambar hanya tersedia untuk kategori baru.</p></div></div>
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="category-name" class="mb-2 block text-sm font-medium text-slate-700">Nama kategori <span class="text-red-500">*</span></label>
                    <input id="category-name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Desain Kreatif" required class="w-full rounded-xl border-slate-200 px-4 py-3 text-slate-800 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="icon" class="mb-2 block text-sm font-medium text-slate-700">Ikon kategori <span class="font-normal text-slate-400">(opsional)</span></label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-slate-300 px-4 py-3 hover:border-blue-400 hover:bg-blue-50/50">
                        <span class="text-xl">✨</span><span class="min-w-0 flex-1 truncate text-sm text-slate-500" x-text="iconName || 'Pilih file ikon'"></span>
                        <span class="text-xs font-semibold text-blue-600">PILIH</span>
                        <input id="icon" type="file" name="icon" accept="image/png,image/jpeg,image/webp" class="hidden" @change="iconName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-2 text-xs text-slate-400">PNG, JPG, atau WEBP. Maksimal 2 MB. Ikon ini tampil di daftar kategori utama.</p>
                    @error('icon') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="image" class="mb-2 block text-sm font-medium text-slate-700">Logo atau gambar kategori <span class="font-normal text-slate-400">(opsional)</span></label>
                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-dashed border-slate-300 px-4 py-3 hover:border-blue-400 hover:bg-blue-50/50">
                        <span class="text-xl">🖼</span><span class="min-w-0 flex-1 truncate text-sm text-slate-500" x-text="imageName || 'Pilih file gambar'"></span>
                        <span class="text-xs font-semibold text-blue-600">PILIH</span>
                        <input id="image" type="file" name="image" accept="image/png,image/jpeg,image/webp" class="hidden" @change="imageName = $event.target.files[0]?.name || ''">
                    </label>
                    <p class="mt-2 text-xs text-slate-400">PNG, JPG, atau WEBP. Maksimal 2 MB.</p>
                    @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-6 rounded-xl bg-slate-50 p-4">
                <label for="subcategory_name" class="mb-1 block text-sm font-medium text-slate-700">Subkategori pertama <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="subcategory_name" type="text" name="subcategory_name" value="{{ old('subcategory_name') }}" placeholder="Contoh: Desain Logo" class="w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <p class="mt-2 text-xs text-slate-400">Isi jika ingin membuat kategori dan subkategori sekaligus.</p>
                @error('subcategory_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><a href="{{ route('admin.categories.index') }}" class="rounded-xl px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</a><button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">Simpan kategori</button></div>
        </form>
    </div>

    <div x-show="mode === 'subcategory'" x-cloak class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <form method="POST" action="{{ route('admin.subcategories.store') }}" class="p-6 sm:p-8">
            @csrf
            <div class="flex items-center gap-3 mb-7"><span class="flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 font-bold text-violet-600">2</span><div><h2 class="font-semibold text-slate-900">Tambahkan subkategori</h2><p class="text-sm text-slate-500">Pilih salah satu kategori yang sudah dibuat.</p></div></div>
            <div class="grid gap-6 md:grid-cols-2">
                <div><label for="subcategory-name" class="mb-2 block text-sm font-medium text-slate-700">Nama subkategori <span class="text-red-500">*</span></label><input id="subcategory-name" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Ilustrasi Digital" required class="w-full rounded-xl border-slate-200 px-4 py-3 outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-100">@error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
                <div><label for="category-id" class="mb-2 block text-sm font-medium text-slate-700">Masukkan ke kategori <span class="text-red-500">*</span></label><select id="category-id" name="category_id" required class="w-full rounded-xl border-slate-200 px-4 py-3 text-slate-700 outline-none focus:border-violet-500 focus:ring-4 focus:ring-violet-100"><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror</div>
            </div>
            @if($categories->isEmpty())<p class="mt-5 rounded-xl bg-amber-50 p-4 text-sm text-amber-700">Belum ada kategori. Buat kategori baru terlebih dahulu.</p>@endif
            <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><a href="{{ route('admin.categories.index') }}" class="rounded-xl px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-100">Batal</a><button type="submit" @disabled($categories->isEmpty()) class="rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700 disabled:cursor-not-allowed disabled:bg-slate-300">Simpan subkategori</button></div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

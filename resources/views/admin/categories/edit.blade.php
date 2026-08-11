@extends('layouts.app')

@section('title', 'Edit Kategori - Admin')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Kategori</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded p-2 focus:ring focus:ring-blue-200" required>
        </div>
        <div class="mb-4">
            <label for="icon" class="block text-sm font-medium mb-1">Ikon kategori</label>
            @if($category->iconIsFile())
                <img src="{{ asset('storage/' . $category->icon) }}" alt="Ikon {{ $category->name }}" class="mb-3 h-20 w-20 rounded-lg object-cover">
            @elseif($category->icon)
                <div class="mb-3 flex h-20 w-20 items-center justify-center rounded-lg border text-3xl">{{ $category->icon }}</div>
            @endif
            <input id="icon" type="file" name="icon" accept="image/png,image/jpeg,image/webp" class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
            <p class="mt-1 text-xs text-gray-500">PNG, JPG, atau WEBP, maksimal 2 MB. Kosongkan jika tidak ingin mengubah ikon.</p>
            @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Logo atau gambar kategori</label>
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="mb-3 h-20 w-20 rounded-lg object-cover">
            @endif
            <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
            <p class="mt-1 text-xs text-gray-500">Opsional. PNG, JPG, atau WEBP, maksimal 2 MB.</p>
            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

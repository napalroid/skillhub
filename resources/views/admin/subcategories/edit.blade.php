@extends('layouts.app')

@section('title', 'Edit Subkategori - Admin')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Subkategori</h1>
    <form method="POST" action="{{ route('admin.subcategories.update', $subcategory) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Nama Subkategori</label>
            <input type="text" name="name" value="{{ old('name', $subcategory->name) }}" class="w-full border rounded p-2 focus:ring focus:ring-blue-200" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Kategori</label>
            <select name="category_id" class="w-full border rounded p-2" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $subcategory->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.subcategories.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
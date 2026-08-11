@extends('layouts.app')

@section('title', 'Kelola Subkategori - Admin')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Kelola Subkategori</h1>
        <a href="{{ route('admin.subcategories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Subkategori</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4">{{ session('error') }}</div>
    @endif

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2 text-left">ID</th>
                <th class="border p-2 text-left">Nama Subkategori</th>
                <th class="border p-2 text-left">Kategori</th>
                <th class="border p-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subcategories as $sub)
                <tr>
                    <td class="border p-2">{{ $sub->id }}</td>
                    <td class="border p-2">{{ $sub->name }}</td>
                    <td class="border p-2">{{ $sub->category->name ?? '-' }}</td>
                    <td class="border p-2 text-center">
                        <a href="{{ route('admin.subcategories.edit', $sub) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('admin.subcategories.destroy', $sub) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center p-4 text-gray-500">Belum ada subkategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
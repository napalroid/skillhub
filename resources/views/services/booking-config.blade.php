@extends('layouts.app')

@section('title', 'Konfigurasi Booking - ' . $service->title)

@section('content')
<div class="max-w-4xl mx-auto px-5 py-8">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-heading text-3xl font-extrabold text-black">Konfigurasi Booking</h1>
        <p class="mt-2 text-text-secondary">Atur field yang harus diisi buyer saat memesan jasa "{{ $service->title }}"</p>
        
        <div class="mt-4 flex items-center gap-4 text-sm">
            <a href="{{ route('services.my') }}" class="inline-flex items-center gap-1 text-[#0051BA] hover:underline">
                ← Kembali ke Jasa Saya
            </a>
            <span class="text-border">|</span>
            <a href="{{ route('services.edit', $service->id) }}" class="text-text-secondary hover:underline">
                Edit Jasa
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded border border-border bg-white p-4">
            <p class="text-sm text-text-secondary">Total Field</p>
            <p class="mt-1 font-heading text-2xl font-bold text-black">{{ count($service->getBookingFields()) }}</p>
        </div>
        <div class="rounded border border-border bg-white p-4">
            <p class="text-sm text-text-secondary">Status Jasa</p>
            <p class="mt-1 font-heading text-2xl font-bold {{ $service->status === 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                {{ $service->status === 'approved' ? 'Aktif' : ucfirst($service->status) }}
            </p>
        </div>
        <div class="rounded border border-border bg-white p-4">
            <p class="text-sm text-text-secondary">Terakhir Diupdate</p>
            <p class="mt-1 font-heading text-sm font-bold text-black">
                {{ $service->last_booking_config_edit ? $service->last_booking_config_edit->diffForHumans() : 'Belum pernah' }}
            </p>
        </div>
    </div>

    {{-- Template Suggestions --}}
    @if(!empty($suggestedTemplates))
    <div class="mb-6 rounded border border-[#0051BA]/20 bg-[#0051BA]/5 p-4">
        <p class="font-heading text-sm font-bold text-[#0051BA]">TEMPLATE DISARANKAN</p>
        <p class="mt-1 text-sm text-text-secondary">Jasa Anda termasuk kategori <strong>{{ $service->subcategory->category->name ?? 'Tidak diketahui' }}</strong>. Template berikut mungkin cocok:</p>
        @foreach($suggestedTemplates as $key => $template)
            @if($template)
            <div class="mt-3 flex items-center justify-between gap-4 rounded border border-border bg-white p-3">
                <div>
                    <p class="font-heading text-sm font-bold text-black">{{ $template['name'] }}</p>
                    <p class="mt-1 text-xs text-text-secondary">{{ count($template['fields'] ?? []) }} field siap pakai</p>
                </div>
                <a href="{{ route('services.edit', $service->id) }}?template={{ $key }}" class="shrink-0 rounded border border-[#0051BA] bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-[#0051BA] transition hover:bg-[#0051BA] hover:text-white">
                    Gunakan Template
                </a>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    {{-- Livewire Component --}}
    <livewire:booking.config-builder :service="$service" />

    {{-- Tips --}}
    <div class="mt-8 rounded border border-border bg-bg-muted p-4">
        <p class="font-heading text-sm font-bold text-black mb-2">Tips Konfigurasi Booking</p>
        <ul class="space-y-2 text-sm text-text-secondary">
            <li class="flex items-start gap-2">
                <span class="mt-0.5 text-[#0051BA]">•</span>
                <span>Field wajib ditandai dengan * (bintang)</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 text-[#0051BA]">•</span>
                <span>Maksimal 15 field per jasa</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 text-[#0051BA]">•</span>
                <span>Field tidak bisa dihapus jika masih ada booking aktif yang menggunakannya</span>
            </li>
            <li class="flex items-start gap-2">
                <span class="mt-0.5 text-[#0051BA]">•</span>
                <span>Pastikan field relevan dengan jenis jasa Anda</span>
            </li>
        </ul>
    </div>
</div>
@endsection

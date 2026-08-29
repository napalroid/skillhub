@extends('layouts.app')

@section('title', 'Riwayat Notifikasi - SkillHub')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat pengajuan jasa dan statusnya dari admin.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
            &larr; Kembali ke dashboard
        </a>
    </div>

        <div class="bg-white rounded-xl border border-gray-100 divide-y divide-gray-100 shadow-sm overflow-hidden">
        @forelse ($notifications as $notification)
            @php
                $showBadge = !in_array($notification->type, ['service_removed_from_subcategory', 'service_deleted']);
                $badge = match ($notification->type) {
                    'approved' => ['text' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'rejected' => ['text' => 'Ditolak', 'class' => 'bg-red-50 text-red-700 border-red-200', 'dot' => 'bg-red-500'],
                    'service_disabled' => ['text' => 'Dinonaktifkan', 'class' => 'bg-gray-100 text-gray-700 border-gray-200', 'dot' => 'bg-gray-500'],
                    'message'  => ['text' => 'Pesan Masuk', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500'],
                    'payment_paid' => ['text' => 'Jasa Terbayarkan', 'class' => 'bg-black text-white border-black', 'dot' => 'bg-white'],
                    'escrow_ready' => ['text' => 'Saldo Masuk — Kerjakan', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'order_confirmed' => ['text' => 'Pesanan Dikonfirmasi', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'order_escrow' => ['text' => 'Konfirmasi Admin', 'class' => 'bg-[#E4002B]/10 text-[#E4002B] border-[#E4002B]/20', 'dot' => 'bg-[#E4002B]'],
                    default   => ['text' => 'Menunggu Approval', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-400'],
                };
                $icon = match ($notification->type) {
                    'approved' => '✓',
                    'rejected' => '✕',
                    'service_disabled' => '⏸',
                    'message'  => '✉',
                    'payment_paid' => '₿',
                    'escrow_ready' => '⚡',
                    'order_confirmed' => '▶',
                    'order_escrow' => '●',
                    'service_removed_from_subcategory' => 'ⓘ',
                    'service_deleted' => 'ⓘ',
                    default   => '•••',
                };
                $iconColor = match ($notification->type) {
                    'approved' => 'bg-emerald-100 text-emerald-600',
                    'rejected' => 'bg-red-100 text-red-600',
                    'service_disabled' => 'bg-gray-100 text-gray-600',
                    'message'  => 'bg-blue-100 text-blue-600',
                    'payment_paid' => 'bg-black text-white',
                    'escrow_ready' => 'bg-emerald-100 text-emerald-600',
                    'order_confirmed' => 'bg-emerald-100 text-emerald-600',
                    'order_escrow' => 'bg-[#E4002B]/10 text-[#E4002B]',
                    'service_removed_from_subcategory' => 'bg-gray-100 text-gray-600',
                    'service_deleted' => 'bg-gray-100 text-gray-600',
                    default => 'bg-amber-100 text-amber-600',
                };

                $notificationLink = null;
                if ($notification->type === 'message' && $notification->conversation) {
                    $notificationLink = route('notifications.open', $notification);
                } elseif (in_array($notification->type, ['payment_paid', 'escrow_ready', 'order_escrow', 'order_confirmed'], true) && $notification->order) {
                    $notificationLink = route('orders.show', $notification->order);
                }
            @endphp
            @if ($notificationLink)
                <a href="{{ $notificationLink }}" class="p-5 flex items-start gap-4 transition hover:bg-blue-50/40 {{ $notification->isUnread() ? 'bg-blue-50/60' : '' }}">
            @else
                <div class="p-5 flex items-start gap-4 {{ $notification->isUnread() ? 'bg-blue-50/60' : '' }}">
            @endif
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-lg font-bold {{ $iconColor }}">
                    {{ $icon }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-semibold text-gray-800">{{ $notification->title }}</h3>
                        @if ($showBadge)
                            <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $badge['class'] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $badge['dot'] }}"></span>
                                {{ $badge['text'] }}
                            </span>
                        @endif
                        @if ($notification->isUnread())
                            <span class="inline-flex rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white">Baru</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                    <p class="mt-2 text-xs text-gray-400">{{ $notification->created_at->format('d M Y, H:i') }} &middot; {{ $notification->created_at->diffForHumans() }}</p>
                </div>
            @if ($notificationLink)
                </a>
            @else
                </div>
            @endif
        @empty
            <div class="p-10 text-center">
                <p class="text-4xl mb-3">🔔</p>
                <p class="text-gray-500 font-medium">Belum ada notifikasi.</p>
                <p class="text-sm text-gray-400 mt-1">Ajukan jasa baru untuk memulai riwayat notifikasi.</p>
                <a href="{{ route('services.create') }}" class="inline-block mt-4 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-blue-700 transition">Ajukan Jasa</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $notifications->links() }}</div>
</div>
@endsection

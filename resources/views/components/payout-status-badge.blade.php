@props(['status'])

@php
    $map = [
        'pending'    => ['label' => 'Menunggu',   'dot' => 'bg-gray-400',   'text' => 'text-gray-600'],
        'processing' => ['label' => 'Memproses',  'dot' => 'bg-gray-900',   'text' => 'text-gray-900'],
        'completed'  => ['label' => 'Berhasil',   'dot' => 'bg-green-600',  'text' => 'text-green-700'],
        'failed'     => ['label' => 'Gagal',      'dot' => 'bg-red-600',    'text' => 'text-red-700'],
        'rejected'   => ['label' => 'Ditolak',    'dot' => 'bg-gray-400',   'text' => 'text-gray-500'],
    ];
    $cfg = $map[$status] ?? $map['pending'];
@endphp

<span class="inline-flex items-center gap-2 text-xs font-medium {{ $cfg['text'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $cfg['dot'] }}"></span>
    {{ $cfg['label'] }}
</span>

@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'bg-slate-50 text-slate-600 border-slate-200',
        'info' => 'bg-blue-50 text-blue-700 border-blue-200',
        'success' => 'bg-green-50 text-green-700 border-green-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger' => 'bg-red-50 text-red-700 border-red-200',
    ];

    $classes = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium border ' . ($variants[$variant] ?? $variants['default']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

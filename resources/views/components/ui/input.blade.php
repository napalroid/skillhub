@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'error' => null,
])

<div {{ $attributes->only('class')->merge(['class' => '']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->except('class')->merge([
            'class' => 'w-full rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-colors ' . ($error ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : ''),
        ]) }}
    />

    @if ($error)
        <p class="text-red-600 text-sm mt-1.5">{{ $error }}</p>
    @endif
</div>

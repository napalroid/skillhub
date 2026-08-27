@props([
    'href' => null,
    'class' => '',
    'imageClass' => '',
    'surface' => 'light',
])

<a href="{{ $href ?? route('home') }}" {{ $attributes->merge(['class' => 'skillhub-brand ' . $class]) }}>
    <span class="skillhub-brand-label">SKILLHUB</span>
</a>

@props([
    'href' => null,
    'class' => '',
    'imageClass' => '',
    'surface' => 'light',
])

<a href="{{ $href ?? route('home') }}" {{ $attributes->merge(['class' => 'skillhub-brand skillhub-brand--' . $surface . ' ' . $class]) }}>
    <span class="skillhub-brand-mark {{ $imageClass }}" aria-hidden="true">S</span>
    <span class="skillhub-brand-label">SkillHub</span>
</a>

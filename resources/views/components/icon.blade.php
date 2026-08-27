@props(['name' => null])

@if ($name)
    @php
        // Convert tabler:brand-instagram to brand-instagram
        $iconName = str_replace('tabler:', '', $name);
        // Convert dash-case to PascalCase for Tabler Icons
        $iconPath = str_replace('-', ' ', $iconName);
        $iconPath = str_replace(' ', '', ucwords($iconPath));
        // Some icons need special handling
        if ($iconName === 'brand-x') $iconPath = 'BrandX';
        if ($iconName === 'brand-youtube') $iconPath = 'BrandYoutube';
        if ($iconName === 'brand-tiktok') $iconPath = 'BrandTiktok';
        if ($iconName === 'brand-facebook') $iconPath = 'BrandFacebook';
        if ($iconName === 'brand-instagram') $iconPath = 'BrandInstagram';
    @endphp
    
    @svg($iconName, $attributes->merge(['class' => 'w-5 h-5']))
@else
    {{ $slot }}
@endif

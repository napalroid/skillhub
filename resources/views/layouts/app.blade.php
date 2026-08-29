<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SkillHub') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="font-sans antialiased h-full bg-gray-50 pt-16 text-gray-900">
    <div class="min-h-screen flex flex-col">
        
        <!-- Navigation -->
        @unless (View::hasSection('hideNavigation'))
            <div id="skillhub-staggered-menu"
                 data-home="{{ route('home') }}"
                 data-marketplace="{{ route('services.index') }}"
                 data-chat="{{ route('conversations.seller-index') }}"
                 data-login="{{ route('login') }}"
                 data-register="{{ route('register') }}"
                 data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
                 data-user-id="{{ auth()->id() ?? '' }}"
                 data-user-name="{{ auth()->user()?->name ?? '' }}"
                 data-avatar-url="{{ auth()->user()?->avatar_url ?? '' }}"
                 data-profile-url="{{ route('profile.edit') }}"
                 data-logout-url="{{ route('logout') }}"
                 data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
                 data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
                 data-dompet="{{ route('wallet.index') }}"
                 data-pesanan="{{ route('orders.index') }}"
                 data-csrf-token="{{ csrf_token() }}"
                 data-is-admin="{{ auth()->user()?->isAdmin() ? 'true' : 'false' }}"
                 data-admin-dashboard="{{ auth()->user()?->isAdmin() ? route('admin.dashboard') : '' }}"></div>
            <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
        @endunless

        <x-notification-toast />

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-grow">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        <!-- Footer -->
        @hasSection('pageFooter')
            @yield('pageFooter')
        @else
            <x-site-footer />
        @endif
    </div>

    <!-- Session Status Toast (Optional Simple Implementation) -->
    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-md shadow-lg z-50 transition-opacity duration-300">
            {{ session('status') }}
        </div>
    @endif

    @stack('scripts')
</body>
</html>

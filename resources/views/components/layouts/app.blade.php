<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SkillHub - Marketplace Jasa Sekolah' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN (cepat, no build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Style tambahan -->
    <style>
        body {
            background-color: #f8fafc;
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        .shadow-card {
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }
        .shadow-card:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col pt-16">

    <!-- ====== NAVBAR ====== -->
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

    <!-- ====== MAIN CONTENT ====== -->
    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <x-notification-toast />
        <!-- Flash Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        {{-- Konten halaman yang dikirim oleh Blade component. --}}
        {{ $slot }}

        <!-- Konten halaman berbasis layout tetap didukung. -->
        @yield('content')
    </main>

    <!-- ====== FOOTER ====== -->
    <x-site-footer />

    <!-- ====== SCRIPTS ====== -->
    @stack('scripts')
</body>
</html>

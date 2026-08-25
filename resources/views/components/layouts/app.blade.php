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
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">

    <!-- ====== NAVBAR ====== -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <x-brand-logo :href="route('home')" class="transition hover:opacity-85" />
                </div>

                <!-- Middle Links (Desktop) -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Daftar Jasa</a>
                    @auth
                        <a href="{{ route('services.my') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Jasa Saya</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Pesanan</a>
                        <a href="{{ route('conversations.seller-index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Chat</a>
                        <a href="{{ route('wallet.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Dompet</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Admin Panel</a>
                        @endif
                    @endauth
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-4">
                    @auth
                        <x-notification-bell />
                        <a href="{{ route('wallet.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-gray-900 hover:text-blue-600 transition" title="Saldo Dompet">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            Rp{{ number_format(auth()->user()->balance, 0, ',', '.') }}
                        </a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow">Register</a>
                    @else
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 hidden group-hover:block transition-all z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil</a>
                                <a href="{{ route('services.my') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Jasa Saya</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Pesanan</a>
                                <a href="{{ route('conversations.seller-index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Chat</a>
                                <a href="{{ route('wallet.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Dompet</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-toggle" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-100">
                <div class="flex flex-col space-y-3 pt-3">
                    <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Daftar Jasa</a>
                    @auth
                        <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Notifikasi</a>
                        <a href="{{ route('conversations.seller-index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Chat</a>
                        <a href="{{ route('wallet.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Dompet</a>
                        <a href="{{ route('services.my') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Jasa Saya</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Pesanan</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">Admin Panel</a>
                        @endif
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 font-medium w-full text-left">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-blue-600 font-medium">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            if (toggleBtn && mobileMenu) {
                toggleBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

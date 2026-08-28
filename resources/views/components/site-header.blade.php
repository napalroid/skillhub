<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('services.index') }}" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Jelajahi Jasa</a>
                <a href="#cara-kerja" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Cara Kerja</a>
                <a href="#escrow" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Escrow</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Dashboard</a>
                    <a href="{{ route('wallet.index') }}" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">Dompet</a>
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-600 bg-white hover:text-slate-900 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <x-ui.button variant="ghost" href="{{ route('login') }}">Masuk</x-ui.button>
                    <x-ui.button href="{{ route('register') }}">Daftar Gratis</x-ui.button>
                @endauth
            </div>

            <button
                type="button"
                class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
                @click="mobileOpen = !mobileOpen"
                aria-label="Menu"
            >
                <svg x-show="!mobileOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-slate-200 py-4 space-y-1">
            <a href="{{ route('services.index') }}" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Jelajahi Jasa</a>
            <a href="#cara-kerja" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600" @click="mobileOpen = false">Cara Kerja</a>
            <a href="#escrow" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600" @click="mobileOpen = false">Escrow</a>
            
            <div class="pt-3 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('wallet.index') }}" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Dompet</a>
                    <a href="{{ route('profile.edit') }}" class="block px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-2 py-2 text-sm text-slate-600 hover:text-blue-600">Keluar</button>
                    </form>
                @else
                    <x-ui.button variant="secondary" href="{{ route('login') }}">Masuk</x-ui.button>
                    <x-ui.button href="{{ route('register') }}">Daftar Gratis</x-ui.button>
                @endauth
            </div>
        </div>
    </div>
</header>

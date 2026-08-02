<x-guest-layout
    variant="forgot"
    title="Lupa Password?"
    subtitle="Masukkan email terdaftar. Kami akan kirim link untuk membuat password baru."
>
    @if (session('status'))
        <div class="mb-5 flex items-start gap-3 text-sm text-green-700 bg-green-50 border border-green-200 px-4 py-3 rounded-lg">
            <svg class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <x-ui.input
            label="Email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            placeholder="nama@sekolah.sch.id"
            required
            autofocus
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <x-ui.button type="submit" class="w-full !py-3">
            Kirim Link Reset Password
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </x-ui.button>
    </form>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400">atau</span></div>
    </div>

    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 text-sm text-blue-600 font-semibold hover:text-blue-700 transition-colors">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke halaman Masuk
    </a>
</x-guest-layout>

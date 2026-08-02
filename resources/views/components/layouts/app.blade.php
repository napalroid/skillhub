<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SkillHub' }} - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <nav class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-700">SkillHub</a>

        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('services.index') }}" class="text-gray-600 hover:text-blue-700">Jasa</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-blue-700">Dashboard Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-red-600 hover:text-red-800">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-700">Masuk</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Daftar</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-4 px-4">
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-4xl mx-auto mt-4 px-4">
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>

</body>
</html>
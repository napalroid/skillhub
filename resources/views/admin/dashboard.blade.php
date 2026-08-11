<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SkillHub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            background: #f8f8ff;
        }
    </style>
</head>

<body class="min-h-screen text-slate-900">
    <div class="min-h-screen border border-blue-600 rounded-md overflow-hidden bg-[#f8f8ff]">

        {{-- NAVBAR --}}
        <header class="bg-white border-b border-slate-200">
            <div class="mx-auto max-w-7xl px-5">
                <div class="h-16 flex items-center justify-between">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('admin.dashboard') }}" class="leading-tight">
                            <p class="font-bold text-lg text-slate-900">SkillHub</p>
                            <p class="text-[9px] text-slate-500">Admin Portal</p>
                        </a>

                        <nav class="hidden md:flex items-center h-16 gap-6 text-xs text-slate-500">
                            <a href="{{ route('admin.dashboard') }}" class="h-16 inline-flex items-center border-b-2 border-blue-600 text-blue-700 font-semibold">
                                Dashboard
                            </a>

                            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-700 transition">
                                Categories
                            </a>

                            <a href="{{ route('admin.subcategories.index') }}" class="hover:text-blue-700 transition">
                                Subcategories
                            </a>

                            <a href="{{ route('admin.payments.index') }}" class="hover:text-blue-700 transition">
                                Transactions
                            </a>

                            <a href="{{ route('admin.reports.index') }}" class="hover:text-blue-700 transition">
                                Reports
                            </a>
                        </nav>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="text-slate-500 hover:text-blue-700" title="Lihat website">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6 6 0 0 0-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 0 1-6 0v-1m6 0H9"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50" title="Keluar dari akun">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3-3h8.25m0 0-3-3m3 3-3 3" />
                                </svg>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>

                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-5 py-6 lg:py-8">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Platform Overview</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan SkillHub hari ini.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10m-12 9h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/>
                        </svg>
                        Hari ini, {{ now()->format('d M Y') }}
                    </span>

                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-800 transition">
                        <span class="text-base leading-none">+</span>
                        Tambah Kategori
                    </a>
                </div>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- STATISTIC --}}
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                Siswa Terdaftar
                            </p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ number_format($totalStudents, 0, ',', '.') }}
                            </p>
                            <p class="mt-2 text-xs text-emerald-600">Akun siswa aktif di platform</p>
                        </div>

                        <span class="rounded-lg bg-blue-50 p-2 text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14 3 9l9-5 9 5-9 5Zm-6 2.5V19c0 1.66 2.69 3 6 3s6-1.34 6-3v-2.5"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                Jasa Aktif
                            </p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ number_format($totalServices, 0, ',', '.') }}
                            </p>
                            <p class="mt-2 text-xs text-emerald-600">Jasa yang telah disetujui</p>
                        </div>

                        <span class="rounded-lg bg-blue-50 p-2 text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7h-3V5a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v9a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V9a2 2 0 0 0-2-2ZM9 5h6v2H9V5Z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                Menunggu Verifikasi
                            </p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ number_format($pendingCount, 0, ',', '.') }}
                            </p>
                            <p class="mt-2 text-xs text-amber-600">Pengajuan jasa perlu ditinjau</p>
                        </div>

                        <span class="rounded-lg bg-amber-50 p-2 text-amber-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                Pembayaran Pending
                            </p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ number_format($pendingPayments, 0, ',', '.') }}
                            </p>
                            <p class="mt-2 text-xs text-blue-600">Menunggu verifikasi admin</p>
                        </div>

                        <span class="rounded-lg bg-blue-50 p-2 text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h2m4 0h4M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </section>

            {{-- CONTENT --}}
            <section class="grid grid-cols-1 xl:grid-cols-3 gap-5">

                {{-- APPROVAL QUEUE --}}
                <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <div>
                            <h2 class="font-bold text-slate-900">Antrian Persetujuan Jasa</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Tinjau jasa siswa sebelum dipublikasikan.</p>
                        </div>

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            {{ $pendingCount }} pengajuan
                        </span>
                    </div>

                    @if ($pendingServices->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr class="text-[10px] uppercase tracking-wider text-slate-500">
                                        <th class="px-5 py-3 font-semibold">Informasi Jasa</th>
                                        <th class="px-5 py-3 font-semibold">Penyedia</th>
                                        <th class="px-5 py-3 font-semibold">Diajukan</th>
                                        <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($pendingServices->take(5) as $service)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <span class="w-9 h-9 shrink-0 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">
                                                        {{ strtoupper(substr($service->title, 0, 1)) }}
                                                    </span>

                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-sm text-slate-800 truncate">
                                                            {{ $service->title }}
                                                        </p>
                                                        <p class="text-xs text-slate-500">
                                                            {{ $service->subcategory?->name ?? 'Tanpa kategori' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-xs text-slate-700">
                                                {{ $service->seller?->name ?? 'Tidak diketahui' }}
                                            </td>

                                            <td class="px-5 py-4 text-xs text-slate-500">
                                                {{ $service->created_at->diffForHumans() }}
                                            </td>

                                            <td class="px-5 py-4">
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('admin.services.reject', $service) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" onclick="return confirm('Tolak jasa ini?')" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                                                            Tolak
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.services.approve', $service) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="rounded-md bg-blue-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-800 transition">
                                                            Setujui
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-14 text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-700">Tidak ada pengajuan baru</p>
                            <p class="mt-1 text-sm text-slate-500">Semua jasa telah diproses.</p>
                        </div>
                    @endif
                </div>

                {{-- SUBMISSION REVIEW --}}
                <aside class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-bold text-slate-900">Review Cepat</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Pengajuan terbaru.</p>
                    </div>

                    @if ($pendingServices->isNotEmpty())
                        <div class="divide-y divide-slate-100">
                            @foreach ($pendingServices->take(3) as $service)
                                <div class="p-4">
                                    <div class="flex gap-3">
                                        <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($service->seller?->name ?? 'U', 0, 2)) }}
                                        </span>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-sm text-slate-800 truncate">
                                                {{ $service->seller?->name ?? 'Pengguna' }}
                                            </p>
                                            <p class="text-xs text-slate-500 truncate">
                                                {{ $service->title }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 mt-3">
                                        <form action="{{ route('admin.services.reject', $service) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Tolak jasa ini?')" class="w-full rounded-md border border-red-200 py-1.5 text-[11px] font-semibold text-red-600 hover:bg-red-50 transition">
                                                Tolak
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.services.approve', $service) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full rounded-md bg-blue-700 py-1.5 text-[11px] font-semibold text-white hover:bg-blue-800 transition">
                                                Setujui
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center text-sm text-slate-500">
                            Tidak ada jasa yang menunggu review.
                        </div>
                    @endif

                    <div class="border-t border-slate-200 p-4">
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition">
                            Verifikasi pembayaran
                            <span>→</span>
                        </a>
                    </div>
                </aside>
            </section>
        </main>

        {{-- FOOTER --}}
        <footer class="mt-10 bg-slate-950 text-slate-300">
            <div class="mx-auto max-w-7xl px-5 py-7 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="font-bold text-white">SkillHub Admin</p>
                    <p class="mt-1 text-xs text-slate-400">Empowering student skill marketplace.</p>
                </div>

                <div class="flex gap-5 text-xs">
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-white">Kategori</a>
                    <a href="{{ route('admin.subcategories.index') }}" class="hover:text-white">Subkategori</a>
                    <a href="{{ route('admin.reports.index') }}" class="hover:text-white">Laporan</a>
                </div>

                <p class="text-xs text-slate-500">
                    © {{ date('Y') }} SkillHub. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

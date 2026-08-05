<x-layouts.app title="Dashboard">

    @php
        $serviceStatusMap = [
            'approved' => ['label' => 'Aktif', 'class' => 'border-green-200 bg-green-50 text-green-700'],
            'pending'  => ['label' => 'Menunggu Approval', 'class' => 'border-amber-200 bg-amber-50 text-amber-700'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'border-red-200 bg-red-50 text-red-700'],
        ];

        $orderStatusMap = [
            'menunggu_pembayaran' => 'bg-slate-100 text-slate-600',
            'menunggu_verifikasi' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'dibayar' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'dikerjakan' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'menunggu_persetujuan' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'selesai' => 'bg-green-50 text-green-700 border border-green-200',
        ];
    @endphp

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-800 to-blue-950 shadow-xl shadow-blue-900/20">
        {{-- Watermark --}}
        <div class="pointer-events-none absolute inset-0 select-none" aria-hidden="true">
            <div class="absolute inset-0 -rotate-12 scale-110">
                @for ($i = 0; $i < 24; $i++)
                    <span class="block whitespace-nowrap font-heading font-extrabold uppercase tracking-widest text-[3.5rem] leading-tight text-white/[0.05]">
                        @if ($i % 2 === 0)
                            SMK NEGERI 8 SEMARANG
                        @else
                            &nbsp;&nbsp;&nbsp;&nbsp;SMK NEGERI 8 SEMARANG &nbsp;&nbsp;SKILLHUB
                        @endif
                    </span>
                @endfor
            </div>
            <div class="pointer-events-none absolute -top-24 -right-16 h-80 w-80 rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\"56\" height=\"56\" viewBox=\"0 0 56 56\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.04\"%3E%3Cpath d=\"M28 24v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-28V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 24v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        </div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 p-8 lg:p-12 items-center">
            {{-- Kiri: copy + CTA --}}
            <div class="lg:col-span-7 max-w-xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-200">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                    </svg>
                    SMK Negeri 8 Semarang
                </div>

                <h1 class="mt-5 font-heading text-3xl sm:text-4xl font-bold text-white leading-tight">
                    Halo, <span class="text-amber-300">{{ auth()->user()->name }}</span>
                </h1>
                <p class="mt-3 text-sm sm:text-base text-blue-100 leading-relaxed">
                    Kelola jasa dan pesananmu di satu tempat. Ajukan keahlianmu untuk ditinjau admin, atau temukan jasa dari teman sekolahmu.
                </p>

                {{-- 2 CTA utama saja --}}
                <div class="mt-7 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('services.create') }}" class="group inline-flex items-center justify-center gap-2 rounded-xl bg-amber-400 px-5 py-3 text-sm font-bold text-blue-950 shadow-lg shadow-amber-500/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-amber-300 hover:shadow-xl hover:shadow-amber-500/30">
                        <svg class="h-4 w-4 group-hover:rotate-90 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Ajukan Jasa Baru
                    </a>
                    <a href="{{ route('services.index') }}" class="group inline-flex items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-bold text-white backdrop-blur-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-white/20">
                        Jelajahi Jasa
                        <svg class="h-4 w-4 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>

                {{-- trust indicators --}}
                <div class="mt-7 flex flex-wrap gap-x-5 gap-y-2">
                    @foreach ([
                        ['icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z', 'label' => 'Dana aman escrow'],
                        ['icon' => 'M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5', 'label' => 'Negosiasi harga'],
                        ['icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z', 'label' => 'Review sesudah selesai'],
                    ] as $trust)
                        <span class="inline-flex items-center gap-1.5 text-xs text-blue-100">
                            <svg class="h-3.5 w-3.5 text-amber-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['icon'] }}" />
                            </svg>
                            {{ $trust['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: ilustrasi siswa + chip statistik --}}
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-sm">
                    {{-- Ilustrasi siswa SVG (inline, tanpa file eksternal) --}}
                    <svg viewBox="0 0 340 230" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto drop-shadow-xl" aria-hidden="true">
                        {{-- latar dekoratif --}}
                        <circle cx="252" cy="70" r="95" fill="#FDE68A" opacity="0.9"/>
                        <circle cx="70" cy="195" r="55" fill="#BFDBFE" opacity="0.55"/>
                        <circle cx="300" cy="195" r="34" fill="#FEF3C7" opacity="0.8"/>
                        <path d="M120 52l2.2 5.2 5.3.6-4 3.8 1.1 5.5-4.6-2.9-4.6 2.9 1.1-5.5-4-3.8 5.3-.6z" fill="#FBBF24"/>
                        <path d="M58 120l1.8 4.2 4.4.5-3.3 3.1.9 4.5-3.8-2.4-3.8 2.4.9-4.5-3.3-3.1 4.4-.5z" fill="#FCD34D" opacity="0.8"/>

                        {{-- ===== Siswa 1 (laki-laki) ===== --}}
                        <g>
                            <rect x="78" y="150" width="26" height="34" rx="4" fill="#1E3A8A"/>
                            <rect x="70" y="184" width="16" height="10" rx="3" fill="#111827"/>
                            <rect x="96" y="184" width="16" height="10" rx="3" fill="#111827"/>
                            {{-- badan kemeja --}}
                            <path d="M66 92h52l-7 34c-3 5-8 8-19 8s-16-3-19-8l-7-34z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            {{-- kerah + dasi --}}
                            <path d="M84 92l8 13 8-13" fill="#2563EB"/>
                            <path d="M92 105l3 22-3 8-3-8 3-22z" fill="#FBBF24"/>
                            {{-- lengan kiri --}}
                            <path d="M66 96l-10 26 14 7 5-30z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            {{-- tangan kiri pegang buku --}}
                            <rect x="36" y="118" width="26" height="19" rx="3" fill="#2563EB"/>
                            <path d="M36 124h26M36 130h26" stroke="#DBEAFE" stroke-width="1.5" stroke-linecap="round"/>
                            <circle cx="44" cy="128" r="6" fill="#FCD5B5"/>
                            {{-- lengan kanan --}}
                            <path d="M118 96l10 26-14 7-5-30z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            <circle cx="130" cy="126" r="6" fill="#FCD5B5"/>
                            {{-- kepala + rambut --}}
                            <circle cx="92" cy="58" r="19" fill="#FCD5B5"/>
                            <path d="M73 52a19 19 0 0 1 38 0c-5-6-11-8-19-8s-14 2-19 8z" fill="#111827"/>
                            <path d="M73 50c0-4 3-7 7-7-2 5-3 10-7 15v-8z" fill="#111827"/>
                            {{-- leher --}}
                            <rect x="87" y="76" width="10" height="11" rx="3" fill="#FCD5B5"/>
                        </g>

                        {{-- ===== Siswa 2 (perempuan) ===== --}}
                        <g>
                            <path d="M212 154l17 26h-14l-13-24z" fill="#1E3A8A"/>
                            <path d="M258 154l-17 26h14l13-24z" fill="#1E3A8A"/>
                            <rect x="222" y="150" width="26" height="38" rx="4" fill="#1E3A8A"/>
                            <rect x="216" y="184" width="15" height="10" rx="3" fill="#111827"/>
                            <rect x="239" y="184" width="15" height="10" rx="3" fill="#111827"/>
                            {{-- badan kemeja --}}
                            <path d="M206 92h58l-8 34c-4 5-9 8-21 8s-17-3-21-8l-8-34z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            {{-- kerah --}}
                            <path d="M226 92l9 13 9-13" fill="#FBBF24"/>
                            {{-- lengan kanan pegang laptop --}}
                            <path d="M264 96l10 26-14 7-5-30z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            <circle cx="275" cy="126" r="6" fill="#FCD5B5"/>
                            {{-- laptop --}}
                            <rect x="236" y="150" width="56" height="16" rx="3" fill="#F59E0B"/>
                            <rect x="240" y="142" width="48" height="12" rx="2" fill="#DBEAFE" stroke="#93C5FD" stroke-width="1.5"/>
                            <path d="M244 147h9M244 151h13" stroke="#60A5FA" stroke-width="1.5" stroke-linecap="round"/>
                            {{-- lengan kiri --}}
                            <path d="M206 96l-10 26 14 7 5-30z" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1.5"/>
                            <circle cx="195" cy="126" r="6" fill="#FCD5B5"/>
                            {{-- kepala + rambut ponytail --}}
                            <circle cx="235" cy="58" r="19" fill="#FCD5B5"/>
                            <path d="M216 52a19 19 0 0 1 38 0c-5-6-11-8-19-8s-14 2-19 8z" fill="#111827"/>
                            <path d="M216 48c0-4 4-8 9-9-1 5-2 11-9 16v-7z" fill="#111827"/>
                            <circle cx="260" cy="48" r="7" fill="#111827"/>
                            <path d="M258 54c6 2 9 8 8 14-6 0-11-4-12-10l4-4z" fill="#111827"/>
                            {{-- leher --}}
                            <rect x="230" y="76" width="10" height="11" rx="3" fill="#FCD5B5"/>
                        </g>
                    </svg>

                    {{-- floating chip: siswa terdaftar (angka real DB) --}}
                    <div class="absolute -top-2 -right-2 sm:right-0 flex items-center gap-2 rounded-xl border border-white/20 bg-white/90 backdrop-blur px-3 py-2 shadow-lg">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 leading-none">Siswa Terdaftar</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $platformStats['total_user'] }} siswa</p>
                        </div>
                    </div>

                    {{-- floating chip: jasa terdaftar (angka real DB) --}}
                    <div class="absolute -bottom-2 -left-2 sm:left-0 flex items-center gap-2 rounded-xl border border-white/20 bg-white/90 backdrop-blur px-3 py-2 shadow-lg">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-400 text-blue-950">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 0 0-1.125-1.125H3.375a1.125 1.125 0 0 0-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 leading-none">Jasa Terdaftar</p>
                            <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $platformStats['total_jasa'] }} jasa</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ KOMUNITAS SKILLHUB (angka real DB) ============ --}}
    <section class="mt-8">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-y lg:divide-y-0 divide-slate-100">
                @foreach ([
                    ['label' => 'Siswa Terdaftar', 'value' => $platformStats['total_user'], 'icon' => 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 'color' => 'bg-blue-100 text-blue-700'],
                    ['label' => 'Jasa Terdaftar', 'value' => $platformStats['total_jasa'], 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 0 0-1.125-1.125H3.375a1.125 1.125 0 0 0-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z', 'color' => 'bg-amber-100 text-amber-700'],
                    ['label' => 'Jasa Aktif', 'value' => $platformStats['total_jasa_aktif'], 'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'color' => 'bg-teal-100 text-teal-700'],
                    ['label' => 'Kategori', 'value' => $platformStats['total_kategori'], 'icon' => 'M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z', 'color' => 'bg-blue-100 text-blue-700'],
                ] as $community)
                    <div class="flex items-center gap-4 px-5 py-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $community['color'] }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $community['icon'] }}" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-heading text-2xl font-extrabold text-slate-900 leading-none">{{ $community['value'] }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $community['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ KEUNGGULAN SKILLHUB ============ --}}
    <section class="mt-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
            <div>
                <h2 class="font-heading text-xl font-bold text-slate-900">Kenapa SkillHub?</h2>
                <p class="text-sm text-slate-500 mt-1">Lingkungan marketplace yang aman untuk transaksi antar siswa.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- 1. Escrow --}}
            <div class="group relative overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-blue-100/70 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-600/20 mb-4 transition-transform duration-200 group-hover:scale-110 group-hover:-rotate-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-base font-bold text-slate-900">Pembayaran Escrow</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Dana ditahan admin sekolah sampai buyer menyetujui hasil kerja. Transaksi aman dua arah.</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        Sistem escrow sekolah
                    </span>
                </div>
            </div>

            {{-- 2. Negosiasi --}}
            <div class="group relative overflow-hidden rounded-2xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-amber-100/80 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-400 text-blue-950 shadow-md shadow-amber-400/25 mb-4 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-base font-bold text-slate-900">Negosiasi Harga</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Sepakati harga final dengan seller sebelum transaksi dimulai. Tanpa harga sepihak.</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Tawar sebelum bayar
                    </span>
                </div>
            </div>

            {{-- 3. Review --}}
            <div class="group relative overflow-hidden rounded-2xl border border-teal-100 bg-gradient-to-br from-teal-50 to-white p-6 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full bg-teal-100/80 group-hover:scale-125 transition-transform duration-300"></div>
                <div class="relative">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-500 text-white shadow-md shadow-teal-500/20 mb-4 transition-transform duration-200 group-hover:scale-110 group-hover:-rotate-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    </div>
                    <h3 class="font-heading text-base font-bold text-slate-900">Review & Rating</h3>
                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">Beri rating setelah transaksi selesai. Bangun reputasi jasa di lingkungan sekolah.</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-teal-50 border border-teal-100 px-3 py-1 text-xs font-semibold text-teal-700">
                        5 bintang untuk terbaik
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ STAT RINGKAS ============ --}}
    <section class="mt-12">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $statCards = [
                    [
                        'label' => 'Jasa Aktif',
                        'value' => $stats['jasa_aktif'],
                        'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5a1.125 1.125 0 0 0-1.125-1.125H3.375a1.125 1.125 0 0 0-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
                        'iconBg' => 'bg-blue-100 text-blue-700',
                        'valueClass' => 'text-blue-700',
                        'decor' => 'bg-blue-50',
                    ],
                    [
                        'label' => 'Menunggu Approval',
                        'value' => $stats['jasa_pending'],
                        'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                        'iconBg' => 'bg-amber-100 text-amber-700',
                        'valueClass' => 'text-amber-600',
                        'decor' => 'bg-amber-50',
                    ],
                    [
                        'label' => 'Pesanan Berjalan',
                        'value' => $stats['pesanan_berjalan'],
                        'icon' => 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z',
                        'iconBg' => 'bg-blue-100 text-blue-700',
                        'valueClass' => 'text-blue-700',
                        'decor' => 'bg-blue-50',
                    ],
                    [
                        'label' => 'Pesanan Selesai',
                        'value' => $stats['pesanan_selesai'],
                        'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                        'iconBg' => 'bg-teal-100 text-teal-700',
                        'valueClass' => 'text-teal-600',
                        'decor' => 'bg-teal-50',
                    ],
                ];
            @endphp

            @foreach ($statCards as $card)
                <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-20 w-20 rounded-full {{ $card['decor'] }} group-hover:scale-125 transition-transform duration-300"></div>
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ $card['label'] }}</p>
                            <p class="mt-1.5 font-heading text-3xl font-extrabold {{ $card['valueClass'] }}">{{ $card['value'] }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $card['iconBg'] }} transition-transform duration-200 group-hover:scale-110">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                            </svg>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============ REKOMENDASI JASA DARI SISWA LAIN ============ --}}
    @if ($rekomendasi_jasa->isNotEmpty())
        <section class="mt-12">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-heading text-xl font-bold text-slate-900">Jasa dari Siswa Lain</h2>
                    <p class="text-sm text-slate-500 mt-1">Sedang butuh bantuan? Cek jasa yang tersedia dari teman sekolahmu.</p>
                </div>
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-700 hover:text-blue-800 transition-colors">
                    Lihat semua jasa
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($rekomendasi_jasa as $service)
                    @php
                        $status = $serviceStatusMap[$service->status] ?? $serviceStatusMap['pending'];
                    @endphp
                    <a href="{{ route('services.show', $service) }}" class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-md">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            @if ($service->subcategory)
                                <span class="truncate rounded-lg border border-amber-100 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                    {{ $service->subcategory->name }}
                                </span>
                            @else
                                <span></span>
                            @endif
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                </svg>
                            </span>
                        </div>

                        <h3 class="font-heading text-base font-bold text-slate-900 line-clamp-1 leading-snug">{{ $service->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $service->description }}</p>

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs text-slate-400 truncate">{{ $service->seller->name }}</span>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="text-sm font-bold text-blue-700">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ JASA SAYA ============ --}}
    <section class="mt-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
            <div>
                <h2 class="font-heading text-xl font-bold text-slate-900">Jasa Saya</h2>
                <p class="text-sm text-slate-500 mt-1">Jasa yang pernah kamu ajukan ke marketplace.</p>
            </div>
        </div>

        @if ($jasa_saya->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($jasa_saya as $service)
                    @php
                        $status = $serviceStatusMap[$service->status] ?? $serviceStatusMap['pending'];
                    @endphp
                    <article class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                        <div class="flex items-center justify-between gap-2 mb-3">
                            @if ($service->subcategory)
                                <span class="truncate rounded-lg border border-blue-100 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                    {{ $service->subcategory->name }}
                                </span>
                            @else
                                <span></span>
                            @endif
                            <span class="inline-flex shrink-0 items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $status['class'] }}">
                                @if ($service->status === 'approved')
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                @elseif ($service->status === 'pending')
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                @else
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                @endif
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <h3 class="font-heading text-base font-bold text-slate-900 line-clamp-1 leading-snug">{{ $service->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 flex-1">{{ $service->description }}</p>

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                            <span class="text-sm font-bold text-blue-700">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                            @if ($service->status === 'approved')
                                <a href="{{ route('services.show', $service) }}" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 shadow-sm hover:bg-blue-700 transition-colors">
                                    Lihat
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-xs text-slate-400">Menunggu admin</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl border border-blue-100 bg-blue-50">
                    <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                    </svg>
                </div>
                <h3 class="font-heading text-base font-bold text-slate-900 mt-4">Belum ada jasa</h3>
                <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Ajukan jasa pertamamu sekarang — admin sekolah akan meninjaunya sebelum tampil di marketplace.</p>
                <a href="{{ route('services.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Ajukan Jasa Pertama
                </a>
            </div>
        @endif
    </section>

    {{-- ============ PESANAN TERBARU ============ --}}
    <section class="mt-12">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
            <div>
                <h2 class="font-heading text-xl font-bold text-slate-900">Pesanan Terbaru</h2>
                <p class="text-sm text-slate-500 mt-1">Aktivitas pesanan terakhir yang melibatkanmu.</p>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-700 hover:text-blue-800 transition-colors">
                Semua pesanan
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
            @forelse ($pesanan_terbaru as $order)
                <a href="{{ route('orders.show', $order) }}" class="block">
                    <div class="group flex items-center justify-between gap-4 px-5 py-4 border-b border-slate-100 last:border-0 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4 min-w-0">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 group-hover:border-blue-200 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-800">{{ $order->service->title }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">#{{ $order->id }} &middot; {{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="hidden sm:inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $orderStatusMap[$order->status] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                            <svg class="h-4 w-4 text-slate-300 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm text-slate-500">Belum ada pesanan.</p>
                    <a href="{{ route('services.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-700 hover:text-blue-800 transition-colors">
                        Cari jasa untuk dipesan
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>
    </section>

</x-layouts.app>

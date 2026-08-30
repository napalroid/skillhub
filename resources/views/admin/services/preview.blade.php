<x-layouts.admin>
    @php
        $statusLabels = [
            'approved' => ['label' => 'Aktif', 'class' => 'badge-success'],
            'pending' => ['label' => 'Menunggu', 'class' => 'badge-pending'],
            'rejected' => ['label' => 'Ditolak', 'class' => 'badge-error'],
            'disabled' => ['label' => 'Dinonaktifkan', 'class' => 'badge-error'],
        ];
        $statusInfo = $statusLabels[$service->status] ?? ['label' => $service->status, 'class' => 'badge-neutral'];
    @endphp

    {{-- NAVIGATION --}}
    <div class="mb-6" data-stagger-item>
        <a href="{{ route('admin.services.pending') }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#555555] uppercase tracking-wide hover:text-black transition-colors duration-150 group">
            <span class="transition-transform duration-200 group-hover:-translate-x-1" aria-hidden="true">←</span>
            Kembali ke Antrian
        </a>
    </div>

    {{-- LAYOUT CONTAINER --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        {{-- PRIMARY CONTENT ZONE --}}
        <div class="lg:col-span-8 space-y-10">
            
            {{-- HERO PRESENTATION --}}
            <article class="space-y-6" data-stagger-item>
                {{-- Status & Category Overline --}}
                <div class="flex flex-wrap items-center gap-3">
                    <span class="badge {{ $statusInfo['class'] }} text-[10px]">{{ $statusInfo['label'] }}</span>
                    <span class="text-xs font-bold text-[#999999] uppercase tracking-[0.12em]">
                        {{ $service->subcategory?->category?->name ?? 'Kategori' }} / {{ $service->subcategory?->name ?? 'Subkategori' }}
                    </span>
                </div>

                {{-- Editorial Heading --}}
                <div>
                    <h1 class="font-heading font-bold text-3xl sm:text-4xl lg:text-[2.75rem] text-black leading-[1.1] tracking-tight">
                        {{ $service->title }}
                    </h1>
                </div>

                {{-- Featured Image --}}
                @if ($service->image)
                    <div class="relative w-full aspect-[16/9] bg-[#F5F5F5] border-2 border-black overflow-hidden">
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="relative w-full aspect-[16/9] bg-[#F5F5F5] border border-[#DDDDDD] flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-2 text-[#DDDDDD]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-[#999999] uppercase tracking-wide">Tidak ada gambar</span>
                        </div>
                    </div>
                @endif

                {{-- Price Display --}}
                <div class="py-6 border-t-2 border-b-2 border-black">
                    <div class="flex items-baseline gap-2">
                        <span class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em]">Harga</span>
                        <span class="font-heading font-bold text-4xl sm:text-5xl text-black tracking-tight">
                            Rp{{ number_format($service->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Description --}}
                <div class="space-y-4 pt-2">
                    <h2 class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em]">Deskripsi Jasa</h2>
                    <div class="prose prose-sm max-w-none">
                        <p class="text-base text-[#111111] leading-relaxed whitespace-pre-line">{{ $service->description }}</p>
                    </div>
                </div>
            </article>

            {{-- PORTFOLIO SECTION --}}
            @if ($portfolios->isNotEmpty())
                <section class="pt-8 border-t border-[#DDDDDD]" data-stagger-item>
                    <h2 class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em] mb-6">Portofolio</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($portfolios as $portfolio)
                            <div class="aspect-square overflow-hidden border border-[#DDDDDD] bg-[#F5F5F5]">
                                <img src="{{ asset('storage/' . $portfolio) }}" 
                                     alt="Portofolio {{ $loop->iteration }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        {{-- ADMINISTRATIVE SIDEBAR --}}
        <aside class="lg:col-span-4 space-y-6">
            
            {{-- ACTION PANEL (Sticky on desktop) --}}
            <div class="lg:sticky lg:top-20 space-y-6">
                
                {{-- Primary Actions --}}
                <div class="bg-white border-2 border-black p-6" data-stagger-item>
                    <h3 class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em] mb-4">Admin Actions</h3>
                    <div class="space-y-3">
                        <form action="{{ route('admin.services.approve', $service) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="btn-success w-full justify-center text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.services.reject', $service) }}" method="POST" onsubmit="return confirm('Tolak jasa ini?')" class="w-full">
                            @csrf
                            <button type="submit" class="btn-danger w-full justify-center text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Seller Information --}}
                <div class="bg-white border border-[#DDDDDD] p-6" data-stagger-item>
                    <h3 class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em] mb-4">Penjual</h3>
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center text-sm font-bold font-heading shrink-0">
                            {{ strtoupper(substr($service->seller?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-heading font-bold text-sm text-black truncate">{{ $service->seller?->name ?? 'N/A' }}</p>
                            <p class="text-xs text-[#555555] truncate mt-0.5">{{ $service->seller?->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Service Metadata --}}
                <div class="bg-[#F5F5F5] border border-[#DDDDDD] p-6" data-stagger-item>
                    <h3 class="text-xs font-bold text-[#555555] uppercase tracking-[0.12em] mb-4">Metadata</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-baseline gap-4">
                            <dt class="text-xs text-[#555555] uppercase tracking-wide">Status</dt>
                            <dd class="font-heading font-bold text-xs text-black uppercase tracking-wide">{{ $statusInfo['label'] }}</dd>
                        </div>
                        <div class="flex justify-between items-baseline gap-4">
                            <dt class="text-xs text-[#555555] uppercase tracking-wide">Dibuat</dt>
                            <dd class="font-heading font-bold text-xs text-black">{{ $service->created_at->format('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between items-baseline gap-4">
                            <dt class="text-xs text-[#555555] uppercase tracking-wide">Total Pesanan</dt>
                            <dd class="font-heading font-bold text-xs text-black">{{ $service->orders_count ?? 0 }}</dd>
                        </div>
                        <div class="flex justify-between items-baseline gap-4">
                            <dt class="text-xs text-[#555555] uppercase tracking-wide">Rating</dt>
                            <dd class="font-heading font-bold text-xs text-black">
                                @if ($service->average_rating)
                                    {{ number_format($service->average_rating, 1) }} / 5.0 ({{ $service->reviews_count ?? 0 }} review)
                                @else
                                    Belum ada
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Service ID (Admin Reference) --}}
                <div class="bg-white border border-[#DDDDDD] p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-[#999999] uppercase tracking-wider">Service ID</span>
                        <span class="font-mono text-xs text-black">#{{ $service->id }}</span>
                    </div>
                </div>

            </div>
        </aside>
    </div>
</x-layouts.admin>

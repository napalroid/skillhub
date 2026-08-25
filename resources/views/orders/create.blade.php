<x-layouts.app title="Buat Pesanan — {{ $service->title }}">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:py-14">

        <div class="mb-8">
            <a href="{{ route('services.show', $service) }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-gray-400 transition hover:text-gray-700">
                <span aria-hidden="true">←</span> Kembali ke jasa
            </a>
            <p class="mt-4 text-[10px] font-semibold uppercase tracking-[0.2em] text-blue-600">Buat pesanan</p>
            <h1 class="mt-1.5 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">Pesan jasa ini</h1>
            <p class="mt-2 max-w-xl text-sm leading-6 text-gray-500">Tulis pesan untuk penyedia jasa, lalu lakukan pembayaran aman lewat escrow SkillHub.</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">

            {{-- Kartu Jasa — Double-Bezel outer shell --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-[1.75rem] border border-gray-200/80 bg-gray-50 p-2">
                    <div class="overflow-hidden rounded-[1.4rem] bg-white">
                        <div class="relative aspect-[16/10] bg-gray-100">
                            @if ($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                     class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-300">
                                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-gray-400">{{ $service->subcategory?->name ?? 'Jasa' }}</p>
                            <h2 class="mt-1.5 text-xl font-bold tracking-tight text-gray-900">{{ $service->title }}</h2>
                            <p class="mt-3 text-2xl font-extrabold tracking-tight text-gray-900">Rp{{ number_format($service->price, 0, ',', '.') }}</p>

                            <div class="mt-5 flex flex-wrap gap-1.5">
                                <span class="rounded-full border border-gray-200 px-3 py-1 text-[10px] font-medium text-gray-600">{{ $service->subcategory?->category?->name ?? 'SkillHub' }}</span>
                                <span class="rounded-full border border-gray-200 px-3 py-1 text-[10px] font-medium text-gray-600">{{ $service->orders_count }} kali terjual</span>
                            </div>

                            <div class="mt-5 flex items-center gap-2 border-t border-gray-100 pt-5">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-xs font-bold text-white">{{ mb_strtoupper(mb_substr($service->seller?->name ?? 'S', 0, 1)) }}</span>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $service->seller?->name ?? 'Siswa SkillHub' }}</p>
                                    <p class="text-xs text-gray-500">Penyedia jasa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Form Pesanan — Double-Bezel outer shell --}}
            <section>
                <div class="rounded-[1.75rem] border border-gray-200/80 bg-gray-50 p-2">
                    <div class="rounded-[1.4rem] bg-white p-6 sm:p-8">
                        <form method="POST" action="{{ route('orders.store') }}" x-data="{ sending: false }" @submit="sending = true" class="space-y-6">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">

                            <div>
                                <label for="message" class="mb-2 block text-sm font-bold text-gray-700">Pesan untuk penyedia jasa</label>
                                <textarea name="message" id="message" rows="6" maxlength="1000"
                                          placeholder="Halo, saya tertarik dengan jasa ini. Boleh minta detailnya?"
                                          class="w-full resize-y rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3.5 text-sm leading-6 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100">{{ old('message') }}</textarea>
                                <p class="mt-2 text-xs text-gray-400">Opsional. Bisa langsung bayar, atau diskusikan dulu sebelum menyetujui harga.</p>
                                @error('message')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('services.show', $service) }}"
                                   class="rounded-2xl px-5 py-3 text-center text-sm font-bold text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">
                                    Batal
                                </a>
                                <button type="submit" :disabled="sending"
                                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-900 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-gray-900/10 transition duration-300 ease-[cubic-bezier(0.32,0.72,0,1)] hover:bg-black hover:shadow-xl hover:shadow-gray-900/20 active:scale-[0.98] disabled:opacity-70">
                                    <span x-show="sending" x-cloak>
                                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    </span>
                                    <span x-text="sending ? 'Memproses...' : 'Lanjut pembayaran'"></span>
                                    <span x-show="!sending" aria-hidden="true">→</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50/60 p-4">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-xs leading-5 text-blue-700">Pembayaran dipegang aman di escrow SkillHub. Dana baru cair ke penyedia jasa setelah kamu menyetujui hasil.</p>
                </div>
            </section>
        </div>
    </div>

</x-layouts.app>

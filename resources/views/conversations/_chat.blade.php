@if ($conversation)
    @php
        $partner = $conversation->buyer_id === auth()->id() ? $conversation->seller : $conversation->buyer;
        $isSeller = $conversation->seller_id === auth()->id();
        $timeline = $conversation->messages->map(fn ($message) => (object) ['type' => 'message', 'item' => $message, 'created_at' => $message->created_at])
            ->concat($conversation->priceOffers->map(fn ($offer) => (object) ['type' => 'offer', 'item' => $offer, 'created_at' => $offer->created_at]))
            ->sortBy('created_at')
            ->values();
    @endphp

    <section id="skillhub-chat"
             data-conversation-id="{{ $conversation->id }}"
             data-user-id="{{ auth()->id() }}"
             data-is-seller="{{ $isSeller ? '1' : '0' }}"
             class="flex min-h-0 flex-1 flex-col bg-white">
        <header class="flex items-center justify-between gap-4 border-b border-[#e5e5e5] px-5 py-4 sm:px-8 pt-20">
            <div class="min-w-0">
                <button type="button" @click="mobileView = 'list'" class="mb-1 text-[11px] font-bold uppercase tracking-[.06em] text-black/50 hover:text-black lg:hidden">&larr; Daftar</button>
                <h1 class="truncate text-lg font-extrabold uppercase tracking-[-.02em]">{{ $partner->name }}</h1>
                <p class="mt-0.5 truncate text-[11px] font-bold uppercase tracking-[.08em] text-black/45">{{ $conversation->service->title }}</p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                @if ($isSeller)
                    <button type="button" @click="offerModal = true" class="rounded-full bg-black px-4 py-2 text-[11px] font-bold uppercase tracking-[.06em] text-white transition hover:bg-black/80">Penawaran</button>
                @endif
                <a href="{{ route('services.show', $conversation->service) }}" class="rounded-full border border-[#e5e5e5] px-4 py-2 text-[11px] font-bold uppercase tracking-[.06em] transition hover:bg-black hover:text-white">Lihat jasa</a>
            </div>
        </header>

        <div data-message-list class="flex min-h-[40vh] flex-1 flex-col gap-3 overflow-y-auto px-5 py-6 sm:px-8">
            @foreach ($timeline as $timelineItem)
                @if ($timelineItem->type === 'message')
                    @php($message = $timelineItem->item)
                    <article data-message-id="{{ $message->id }}" class="chat-message {{ $message->sender_id === auth()->id() ? 'chat-message-own' : 'chat-message-other' }}">
                        <span class="chat-message-name">{{ $message->sender_id === auth()->id() ? 'Kamu' : $message->sender->name }}</span>
                        <p>{{ $message->message }}</p>
                        <time>{{ $message->created_at->format('H:i') }}</time>
                    </article>
                @else
                    @php($offer = $timelineItem->item)
                    <article data-offer-id="{{ $offer->id }}" class="my-2 border border-[#e5e5e5] bg-[#f6f6f6] p-4 text-black">
                        <div class="flex items-start justify-between gap-4">
                            <div><p class="text-[10px] font-bold uppercase tracking-[.16em] text-black/55">Penawaran harga</p><h2 class="mt-1 text-sm font-extrabold uppercase">{{ $conversation->service->title }}</h2></div>
                            <span data-offer-status class="border border-[#e5e5e5] bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-[.04em]">{{ $offer->status->value }}</span>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                            <div><dt class="text-black/45">Harga asli</dt><dd class="mt-1 font-bold">Rp{{ number_format($offer->original_price, 0, ',', '.') }}</dd></div>
                            <div><dt class="text-black/45">Harga kesepakatan</dt><dd class="mt-1 font-bold">Rp{{ number_format($offer->offer_price, 0, ',', '.') }}</dd></div>
                        </dl>
                        @if($offer->note)<p class="mt-3 border-t border-[#e5e5e5] pt-3 text-xs leading-5 text-black/70">{{ $offer->note }}</p>@endif
                        @if ($offer->isPending() && ! $offer->isExpired() && !$isSeller)
                            <div data-offer-actions class="mt-4 flex flex-wrap gap-2 border-t border-[#e5e5e5] pt-3">
                                <form method="POST" action="{{ route('price-offers.accept', $offer) }}">@csrf<button class="bg-black px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] text-white hover:bg-black/80">Terima</button></form>
                                <form method="POST" action="{{ route('price-offers.reject', $offer) }}">@csrf<button class="border border-[#e5e5e5] bg-white px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] hover:bg-black hover:text-white">Tolak</button></form>
                            </div>
                        @endif
                    </article>
                @endif
            @endforeach
        </div>

        <form data-chat-form action="{{ route('conversations.store', $conversation) }}" method="POST" class="border-t border-[#e5e5e5] p-3 sm:grid-cols-[1fr_auto] sm:p-4 grid gap-2 bg-white">
            @csrf
            <textarea data-chat-input name="message" maxlength="1500" required placeholder="Tulis pesan..." class="min-h-12 resize-none border border-[#e5e5e5] bg-white p-3 text-sm text-black outline-none focus:border-black"></textarea>
            <button data-chat-submit type="submit" class="bg-black px-6 py-3 text-[11px] font-bold uppercase tracking-[.08em] text-white transition hover:bg-black/80 disabled:opacity-60 sm:px-8">Kirim</button>
        </form>
        <p data-chat-error class="px-4 pb-3 text-xs font-bold text-red-600"></p>
    </section>

    @if ($isSeller)
        <div x-show="offerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @keydown.escape.window="offerModal = false">
            <div @click.outside="offerModal = false" class="w-full max-w-md border border-[#e5e5e5] bg-white p-6 text-black shadow-2xl">
                <div class="flex items-start justify-between gap-4">
                    <div><p class="text-[10px] font-bold uppercase tracking-[.16em] text-black/55">Buat penawaran harga</p><h2 class="mt-1 text-lg font-extrabold uppercase">{{ $conversation->service->title }}</h2></div>
                    <button type="button" @click="offerModal = false" class="text-xl text-black/50 hover:text-black">&times;</button>
                </div>
                <p class="mt-4 text-sm text-black/60">Harga asli jasa: <strong class="text-black">Rp{{ number_format($conversation->service->price, 0, ',', '.') }}</strong></p>
                <form method="POST" action="{{ route('price-offers.store', $conversation) }}" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="offer_price" class="block text-xs font-bold uppercase tracking-[.04em]">Harga kesepakatan</label>
                        <input id="offer_price" type="text" inputmode="numeric" pattern="[0-9]*" name="offer_price" min="1" required class="mt-2 w-full border border-[#e5e5e5] px-3 py-3 text-sm outline-none focus:border-black" placeholder="Contoh: 500000">
                    </div>
                    <div>
                        <label for="note" class="block text-xs font-bold uppercase tracking-[.04em]">Catatan <span class="font-normal text-black/50">(opsional)</span></label>
                        <textarea id="note" name="note" maxlength="1000" rows="3" class="mt-2 w-full resize-none border border-[#e5e5e5] p-3 text-sm outline-none focus:border-black" placeholder="Catatan kesepakatan, detail tambahan, dll"></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="offerModal = false" class="border border-[#e5e5e5] px-4 py-2.5 text-[11px] font-bold uppercase tracking-[.06em] hover:bg-black hover:text-white">Batal</button>
                        <button class="bg-black px-4 py-2.5 text-[11px] font-bold uppercase tracking-[.06em] text-white hover:bg-black/80">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@else
    <section class="flex min-h-0 flex-1 flex-col items-center justify-center bg-white px-6 text-center pt-20">
        <p class="text-[11px] font-bold uppercase tracking-[.2em] text-black/35">SkillHub Messages</p>
        <h2 class="mt-4 text-2xl font-extrabold uppercase tracking-[-.03em] text-black/80">Pilih percakapan</h2>
        <p class="mt-2 max-w-xs text-xs font-medium text-black/45">Pilih satu sesi di sebelah kiri untuk mulai membaca dan membalas pesan.</p>
    </section>
@endif

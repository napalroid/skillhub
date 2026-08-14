@php
    $partner = $conversation->buyer_id === auth()->id() ? $conversation->seller : $conversation->buyer;
    $isSeller = $conversation->seller_id === auth()->id();
    $timeline = $conversation->messages->map(fn ($message) => (object) ['type' => 'message', 'item' => $message, 'created_at' => $message->created_at])
        ->concat($conversation->priceOffers->map(fn ($offer) => (object) ['type' => 'offer', 'item' => $offer, 'created_at' => $offer->created_at]))
        ->sortBy('created_at')
        ->values();
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat {{ $partner->name }} - SkillHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'DM Sans',sans-serif}.chat-message{max-width:78%;padding:.8rem 1rem}.chat-message-own{margin-left:auto;background:#2563eb;color:#fff}.chat-message-other{background:#fff;color:#080808}.chat-message-name,.chat-message time{display:block;font-size:.68rem;font-weight:700;opacity:.68}.chat-message p{margin:.3rem 0;font-size:.88rem;line-height:1.5;white-space:pre-wrap}
    </style>
    @vite('resources/js/app.js')
</head>
<body class="bg-[#080808] text-[#f6f6f6]">
    <main x-data="{ offerModal: false }" class="mx-auto flex min-h-screen max-w-5xl flex-col px-4 py-5 sm:px-8">
        <header class="flex items-center justify-between gap-4 border-b border-white/20 pb-4">
            <div>
                <a href="{{ $isSeller ? route('conversations.seller-index') : route('conversations.index') }}" class="text-xs font-bold text-white/60 hover:text-white">&larr; Semua percakapan</a>
                <h1 class="mt-2 text-xl font-bold tracking-[-.04em]">{{ $partner->name }}</h1>
                <p class="mt-1 text-xs text-white/60">Membahas: {{ $conversation->service->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if ($isSeller)
                    <button type="button" @click="offerModal = true" class="border border-blue-400 bg-blue-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-500">Buat Penawaran</button>
                @endif
                <span class="border border-white/30 px-3 py-2 text-xs font-bold">{{ $conversation->service->subcategory?->name ?? 'Jasa' }}</span>
            </div>
        </header>

        @if (session('success'))
            <p class="mt-4 border border-emerald-400/40 bg-emerald-400/10 px-3 py-2 text-xs text-emerald-100">{{ session('success') }}</p>
        @endif

        <section id="skillhub-chat" data-conversation-id="{{ $conversation->id }}" data-user-id="{{ auth()->id() }}" data-is-seller="{{ $isSeller ? '1' : '0' }}" class="mt-5 flex min-h-0 flex-1 flex-col">
            <div data-message-list class="flex max-h-[62vh] min-h-[48vh] flex-col gap-2 overflow-y-auto bg-[#161616] p-4 sm:p-6">
                @foreach($timeline as $timelineItem)
                    @if($timelineItem->type === 'message')
                        @php($message = $timelineItem->item)
                        <article data-message-id="{{ $message->id }}" class="chat-message {{ $message->sender_id === auth()->id() ? 'chat-message-own' : 'chat-message-other' }}">
                            <span class="chat-message-name">{{ $message->sender_id === auth()->id() ? 'Kamu' : $message->sender->name }}</span>
                            <p>{{ $message->message }}</p>
                            <time>{{ $message->created_at->format('H:i') }}</time>
                        </article>
                    @else
                        @php($offer = $timelineItem->item)
                    <article data-offer-id="{{ $offer->id }}" class="my-3 border border-blue-300/50 bg-blue-50 p-4 text-[#101828] shadow-sm">
                        <div class="flex items-start justify-between gap-4"><div><p class="text-[10px] font-bold tracking-[.16em] text-blue-700">PENAWARAN HARGA</p><h2 class="mt-1 text-sm font-bold">{{ $conversation->service->title }}</h2></div><span data-offer-status class="border border-blue-200 bg-white px-2 py-1 text-[10px] font-bold uppercase text-blue-700">{{ $offer->status->value }}</span></div>
                        <dl class="mt-4 grid grid-cols-2 gap-3 text-xs"><div><dt class="text-black/50">Harga asli</dt><dd class="mt-1 font-bold">Rp{{ number_format($offer->original_price, 0, ',', '.') }}</dd></div><div><dt class="text-black/50">Harga kesepakatan</dt><dd class="mt-1 font-bold text-blue-700">Rp{{ number_format($offer->offer_price, 0, ',', '.') }}</dd></div></dl>
                        @if($offer->note)<p class="mt-3 border-t border-blue-200 pt-3 text-xs leading-5 text-black/70">{{ $offer->note }}</p>@endif
                        @if (! $isSeller && $offer->buyer_id === auth()->id() && $offer->isPending() && ! $offer->isExpired())
                            <div data-offer-actions class="mt-4 flex flex-wrap gap-2 border-t border-blue-200 pt-3">
                                <form method="POST" action="{{ route('price-offers.accept', $offer) }}">@csrf<button class="bg-blue-600 px-3 py-2 text-xs font-bold text-white hover:bg-blue-700">Terima &amp; Pesan</button></form>
                                <form method="POST" action="{{ route('price-offers.reject', $offer) }}">@csrf<button class="border border-black/20 bg-white px-3 py-2 text-xs font-bold hover:bg-black hover:text-white">Tolak</button></form>
                            </div>
                        @endif
                    </article>
                    @endif
                @endforeach
            </div>
            <form action="{{ route('conversations.store', $conversation) }}" method="POST" class="mt-3 grid gap-2 bg-[#f6f6f6] p-3 sm:grid-cols-[1fr_auto]">
                @csrf
                <textarea name="message" maxlength="1500" required placeholder="Tulis pesan..." class="min-h-12 resize-none border border-black/20 bg-white p-3 text-sm text-black outline-none focus:border-black"></textarea>
                <button type="submit" class="bg-[#2563eb] px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700 disabled:opacity-60">Kirim</button>
            </form>
            <p data-chat-error class="mt-2 text-xs text-red-300"></p>
        </section>

        @if ($isSeller)
            <div x-show="offerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" @keydown.escape.window="offerModal = false">
                <div @click.outside="offerModal = false" class="w-full max-w-md bg-white p-6 text-[#101828] shadow-2xl">
                    <div class="flex items-start justify-between gap-4"><div><p class="text-[10px] font-bold tracking-[.16em] text-blue-700">BUAT PENAWARAN HARGA</p><h2 class="mt-1 text-lg font-bold">{{ $conversation->service->title }}</h2></div><button type="button" @click="offerModal = false" class="text-xl text-black/50">&times;</button></div>
                    <p class="mt-4 text-sm text-black/60">Harga asli: <strong class="text-black">Rp{{ number_format($conversation->service->price, 0, ',', '.') }}</strong></p>
                    <form method="POST" action="{{ route('price-offers.store', $conversation) }}" class="mt-5 space-y-4">
                        @csrf
                        <div><label for="offer_price" class="block text-xs font-bold">Harga kesepakatan</label><input id="offer_price" type="number" name="offer_price" min="1" max="{{ $conversation->service->price }}" step="1" required class="mt-2 w-full border border-black/20 px-3 py-3 text-sm outline-none focus:border-blue-600" placeholder="Contoh: 500000"><p class="mt-1 text-[11px] text-black/50">Tidak boleh melebihi harga asli.</p></div>
                        <div><label for="note" class="block text-xs font-bold">Catatan <span class="font-normal text-black/50">(opsional)</span></label><textarea id="note" name="note" maxlength="1000" rows="3" class="mt-2 w-full resize-none border border-black/20 p-3 text-sm outline-none focus:border-blue-600" placeholder="Sesuai kesepakatan di chat"></textarea></div>
                        <div class="flex justify-end gap-2"><button type="button" @click="offerModal = false" class="border border-black/20 px-4 py-2.5 text-xs font-bold">Batal</button><button class="bg-blue-600 px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-700">Buat Penawaran</button></div>
                    </form>
                </div>
            </div>
        @endif
    </main>
</body>
</html>

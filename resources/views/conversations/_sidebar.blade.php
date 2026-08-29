@php
    $filters = [
        'all' => 'Semua',
        'unread' => 'Belum dibaca',
        'read' => 'Sudah dibaca',
    ];
    $activeId = $conversation?->id;
@endphp

<div class="flex flex-col">
    <div class="border-b border-[#e5e5e5] px-5 py-4 pt-20">
        <p class="text-[10px] font-bold uppercase tracking-[.18em] text-black/45">Urutkan pesan</p>
        <div class="mt-3 flex border border-[#e5e5e5]" role="group" aria-label="Filter pesan">
            @foreach ($filters as $key => $label)
                @php($href = route($indexRoute, ['filter' => $key]) . ($activeId ? '#conv-' . $activeId : ''))
                <a href="{{ $href }}"
                   class="flex-1 px-2 py-2 text-center text-[11px] font-bold uppercase tracking-[.04em] transition
                          {{ $filter === $key ? 'bg-black text-white' : 'bg-white text-black hover:bg-black/5' }}
                          {{ ! $loop->last ? 'border-r border-[#e5e5e5]' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="mt-3 inline-flex gap-2">
            <a href="{{ route('conversations.index') }}"
               class="rounded-md border px-4 py-1.5 text-[11px] font-bold uppercase tracking-[.06em] transition {{ $mode === 'buyer' ? 'border-black bg-black text-white' : 'border-[#e5e5e5] bg-white text-black hover:bg-black hover:text-white' }}">Pembeli</a>
            <a href="{{ route('conversations.seller-index') }}"
               class="rounded-md border px-4 py-1.5 text-[11px] font-bold uppercase tracking-[.06em] transition {{ $mode === 'seller' ? 'border-black bg-black text-white' : 'border-[#e5e5e5] bg-white text-black hover:bg-black hover:text-white' }}">Penjual</a>
        </div>
    </div>

    <div class="min-h-0 flex-1 overflow-y-auto">
        @forelse ($conversations as $item)
            @php($partner = $item->buyer_id === auth()->id() ? $item->seller : $item->buyer)
            @php($isUnread = $item->unread_count > 0)
            @php($isActive = $item->id === $activeId)
            <a id="conv-{{ $item->id }}"
               href="{{ route('conversations.show', $item) . '?filter=' . $filter }}"
               @click="mobileView = 'chat'"
               class="block border-b border-[#e5e5e5] px-5 py-4 transition
                      {{ $isActive ? 'bg-black text-white' : ($isUnread ? 'bg-white' : 'bg-[#f6f6f6] hover:bg-white') }}
                      {{ $isActive ? '' : 'hover:bg-white' }}">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-2">
                        @if ($isUnread)
                            <span class="inline-block h-2 w-2 shrink-0 rounded-full {{ $isActive ? 'bg-white' : 'bg-black' }}" aria-label="Belum dibaca"></span>
                        @endif
                        <strong class="truncate text-sm {{ $isUnread && ! $isActive ? 'font-extrabold' : 'font-bold' }}">{{ $partner->name }}</strong>
                    </div>
                    <time class="shrink-0 text-[10px] font-bold uppercase tracking-[.04em] opacity-60">
                        {{ $item->latestMessage?->created_at?->format('d M') ?? $item->created_at->format('d M') }}
                    </time>
                </div>
                <p class="mt-1.5 truncate text-xs font-bold uppercase tracking-[.03em] opacity-70">{{ $item->service->title }}</p>
                <p class="mt-1 truncate text-xs {{ $isActive ? 'text-white/70' : 'text-black/55' }}">
                    {{ $item->latestMessage?->message ?? 'Belum ada pesan.' }}
                </p>
                @if ($isUnread)
                    <span class="mt-2 inline-block bg-black px-1.5 py-0.5 text-[10px] font-bold text-white {{ $isActive ? 'bg-white text-black' : '' }}">
                        {{ $item->unread_count }} baru
                    </span>
                @endif
            </a>
        @empty
            <div class="px-5 py-16 text-center">
                <p class="text-xs font-bold uppercase tracking-[.1em] text-black/40">Tidak ada percakapan</p>
                @if ($filter !== 'all')
                    <a href="{{ route($indexRoute) }}" class="mt-3 inline-block text-[11px] font-bold uppercase underline">Lihat semua</a>
                @endif
            </div>
        @endforelse
    </div>
</div>

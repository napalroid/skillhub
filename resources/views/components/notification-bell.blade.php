{{-- Lonceng notifikasi: badge merah untuk notifikasi belum dibaca. --}}
@auth
@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $recentNotifications = auth()->user()->notifications()->with(['service', 'conversation'])->latest()->take(5)->get();
@endphp
<div class="nf-bell relative" id="nf-bell">
    <button type="button" id="nf-bell-toggle" class="nf-bell-toggle" aria-label="Notifikasi">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
        <span id="nf-bell-badge" class="nf-bell-badge {{ $unreadCount ? '' : 'nf-hidden' }}">{{ min($unreadCount, 9) }}{{ $unreadCount > 9 ? '+' : '' }}</span>
    </button>

    <div id="nf-bell-panel" class="nf-bell-panel">
        <div class="nf-bell-head">
            <span class="nf-bell-head-title">Notifikasi</span>
            <button type="button" id="nf-bell-markall" class="nf-bell-markall" onclick="nfMarkAllRead()">Tandai dibaca</button>
        </div>
        <div class="nf-bell-list">
            @forelse ($recentNotifications as $notification)
                @php
                    $bellLink = $notification->type === 'message' && $notification->conversation
                        ? route('notifications.open', $notification)
                        : route('notifications.index');
                @endphp
                <a href="{{ $bellLink }}" class="nf-bell-item {{ $notification->isUnread() ? 'nf-bell-item--unread' : '' }}">
                    <span class="nf-dot {{ $notification->type === 'approved' ? 'nf-dot--green' : ($notification->type === 'rejected' ? 'nf-dot--red' : ($notification->type === 'message' ? 'nf-dot--blue' : 'nf-dot--yellow')) }}"></span>
                    <span class="nf-bell-item-body">
                        <span class="nf-bell-item-title">{{ $notification->title }}</span>
                        <span class="nf-bell-item-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </span>
                </a>
            @empty
                <p class="nf-bell-empty">Belum ada notifikasi.</p>
            @endforelse
        </div>
        <div class="nf-bell-foot">
            <a href="{{ route('notifications.index') }}">Lihat riwayat notifikasi</a>
        </div>
    </div>
</div>

<style>
    .nf-bell { display: inline-flex; }
    .nf-bell-toggle {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.4rem;
        height: 2.4rem;
        border-radius: .75rem;
        border: 0;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: background .2s, color .2s;
    }
    .nf-bell-toggle:hover { background: #f1f5f9; color: #0f172a; }
    .nf-bell-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 1.05rem;
        height: 1.05rem;
        padding: 0 .25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        background: #ef4444;
        color: #fff;
        font-size: .62rem;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 0 0 2px #fff;
    }
    .nf-hidden { display: none !important; }
    .nf-bell-panel {
        position: absolute;
        top: calc(100% + .6rem);
        right: 0;
        width: 19rem;
        max-width: calc(100vw - 2rem);
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 20px 45px -18px rgba(15,23,42,.35);
        z-index: 60;
        display: none;
        overflow: hidden;
    }
    .nf-bell.nf-open .nf-bell-panel { display: block; animation: nf-pop .25s ease-out; }
    @keyframes nf-pop { from { opacity: 0; transform: translateY(-6px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .nf-bell-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .85rem 1rem .7rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .nf-bell-head-title { font-size: .9rem; font-weight: 700; color: #0f172a; }
    .nf-bell-markall {
        border: 0;
        background: transparent;
        color: #2563eb;
        font-size: .75rem;
        font-weight: 600;
        cursor: pointer;
        transition: color .2s;
    }
    .nf-bell-markall:hover { color: #1d4ed8; }
    .nf-bell-list { max-height: 18rem; overflow-y: auto; padding: .35rem; }
    .nf-bell-item {
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        padding: .6rem .65rem;
        border-radius: .7rem;
        text-decoration: none;
        transition: background .15s;
    }
    .nf-bell-item:hover { background: #f8fafc; }
    .nf-bell-item--unread { background: #eff6ff; }
    .nf-bell-item--unread:hover { background: #dbeafe; }
    .nf-dot { flex: none; width: .55rem; height: .55rem; margin-top: .35rem; border-radius: 9999px; }
    .nf-dot--green { background: #22c55e; }
    .nf-dot--red { background: #ef4444; }
    .nf-dot--yellow { background: #f59e0b; }
    .nf-dot--blue { background: #2563eb; }
    .nf-bell-item-body { min-width: 0; display: flex; flex-direction: column; gap: .1rem; }
    .nf-bell-item-title {
        font-size: .8rem;
        font-weight: 600;
        color: #0f172a;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .nf-bell-item-time { font-size: .7rem; color: #94a3b8; }
    .nf-bell-empty { padding: 1.5rem 1rem; text-align: center; font-size: .8rem; color: #94a3b8; }
    .nf-bell-foot { border-top: 1px solid #f1f5f9; padding: .6rem 1rem; text-align: center; }
    .nf-bell-foot a { font-size: .78rem; font-weight: 600; color: #2563eb; text-decoration: none; transition: color .2s; }
    .nf-bell-foot a:hover { color: #1d4ed8; }
</style>

<script>
    (function () {
        var bell = document.getElementById('nf-bell');
        if (!bell) return;
        var toggle = document.getElementById('nf-bell-toggle');
        var panel = document.getElementById('nf-bell-panel');

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            bell.classList.toggle('nf-open');
        });
        document.addEventListener('click', function (e) {
            if (!bell.contains(e.target)) bell.classList.remove('nf-open');
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') bell.classList.remove('nf-open');
        });

        window.nfMarkAllRead = function () {
            fetch('{{ route('notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(function () {
                var badge = document.getElementById('nf-bell-badge');
                if (badge) badge.classList.add('nf-hidden');
                var items = panel.querySelectorAll('.nf-bell-item--unread');
                items.forEach(function (el) { el.classList.remove('nf-bell-item--unread'); });
            });
        };
    })();
</script>
@endauth

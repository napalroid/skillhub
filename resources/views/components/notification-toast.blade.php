{{-- Toast mengambang di bawah navbar: muncul dari dalam navbar, lalu hilang otomatis. --}}
@php
    $toastTitle = session('notification_submitted');
    $toastMessage = $toastTitle
        ? "Jasa <strong>&quot;{$toastTitle}&quot;</strong> telah terkirim ke admin dan sedang menunggu persetujuan."
        : null;
@endphp

@if ($toastTitle)
    <div id="nf-toast-root" class="nf-toast-root">
        <div class="nf-toast">
            <span class="nf-toast-icon">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
            <div class="nf-toast-body">
                <p class="nf-toast-title">Pengajuan terkirim ke admin!</p>
                <p class="nf-toast-msg">{!! $toastMessage !!}</p>
            </div>
            <button type="button" class="nf-toast-close" onclick="nfToastDismiss()" aria-label="Tutup notifikasi">&times;</button>
        </div>
    </div>

    <style>
        .nf-toast-root {
            position: fixed;
            top: 5rem;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            padding: 0 1rem;
            z-index: 40;
            pointer-events: none;
            transform: translateY(-160%);
            opacity: 0;
            transition: transform .75s cubic-bezier(.22,1,.36,1), opacity .6s ease;
        }
        .nf-toast-root.nf-toast-root--in { transform: translateY(0); opacity: 1; }
        .nf-toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            gap: .85rem;
            max-width: min(92vw, 480px);
            padding: .9rem 1rem;
            border-radius: 1rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 14px 34px -12px rgba(15,23,42,.28);
        }
        .nf-toast-icon {
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: .8rem;
            background: #dbeafe;
            color: #2563eb;
        }
        .nf-toast-body { min-width: 0; }
        .nf-toast-title { font-size: .9rem; font-weight: 700; color: #0f172a; }
        .nf-toast-msg { margin-top: .15rem; font-size: .78rem; line-height: 1.4; color: #64748b; }
        .nf-toast-msg strong { color: #0f172a; }
        .nf-toast-close {
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: .5rem;
            border: 0;
            background: transparent;
            color: #94a3b8;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
            transition: background .2s, color .2s;
        }
        .nf-toast-close:hover { background: #f1f5f9; color: #475569; }

        .nf-toast--out {
            animation: nf-wipe-out 1.5s cubic-bezier(.5,0,.55,.35) forwards;
        }
        @keyframes nf-wipe-out {
            0%   { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 0%, #000 0%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 0%, #000 0%, #000 100%); opacity: 1; transform: translateY(0); }
            20%  { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 20%, #000 20%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 20%, #000 20%, #000 100%); }
            40%  { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 40%, #000 40%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 40%, #000 40%, #000 100%); }
            60%  { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 60%, #000 60%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 60%, #000 60%, #000 100%); }
            80%  { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 80%, #000 80%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 80%, #000 80%, #000 100%); }
            100% { -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 100%, #000 100%, #000 100%); mask-image: linear-gradient(to right, transparent 0%, transparent 100%, #000 100%, #000 100%); opacity: 0; transform: translateY(-8px); }
        }
    </style>

    <script>
        (function () {
            var root = document.getElementById('nf-toast-root');
            if (!root) return;
            var toast = root.querySelector('.nf-toast');

            function dismiss() {
                if (root.dataset.done) return;
                root.dataset.done = '1';
                toast.classList.add('nf-toast--out');
                setTimeout(function () { if (root.parentNode) root.parentNode.removeChild(root); }, 1550);
            }
            window.nfToastDismiss = dismiss;

            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    root.classList.add('nf-toast-root--in');
                });
            });

            setTimeout(dismiss, 5200);
        })();
    </script>
@endif

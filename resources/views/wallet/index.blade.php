<x-layouts.app title="Dompet Saya">
    @include('wallet._styles')

    <div class="wh-page">
        <div class="wh-inner">
            <header class="wh-hero">
                <div>
                    <p class="wh-kicker">Keuangan</p>
                    <span class="wh-rule" aria-hidden="true"></span>
                    <h1 class="wh-display" style="font-size: clamp(2.4rem, 6vw, 4.25rem);">Dompet</h1>
                    <p class="wh-lede">Saldo hasil kerja di SkillHub. Tarik kapan siap, cek setiap pergerakan dari satu daftar.</p>

                    <p class="wh-balance-label" style="margin-top: 2.25rem;">Saldo tersedia</p>
                    <div 
                        data-decrypted-balance
                        data-balance="{{ number_format($balance, 0, ',', '.') }}"
                        data-currency="IDR"
                        class="wh-balance-value"
                    ></div>
                </div>

                <aside class="wh-hero-aside">
                    <a href="{{ route('wallet.withdraw.create') }}" class="wh-btn">Tarik saldo</a>
                    <dl class="wh-facts">
                        <div class="wh-fact">
                            <dt>Aktivitas</dt>
                            <dd>{{ number_format($transactions->total()) }}</dd>
                        </div>
                        <div class="wh-fact">
                            <dt>Minimal tarik</dt>
                            <dd>Rp{{ number_format($minAmount ?? config('payout.min_amount', 5000), 0, ',', '.') }}</dd>
                        </div>
                        <div class="wh-fact">
                            <dt>Biaya</dt>
                            <dd>Rp0</dd>
                        </div>
                        <div class="wh-fact">
                            <dt>Pencairan</dt>
                            <dd>{{ config('payout.processing_delay_seconds', 10) }} detik</dd>
                        </div>
                    </dl>
                    @if (!empty($default['account']))
                        <p class="wh-lede" style="margin-top:0;max-width:none;font-size:.8rem;">
                            Rekening terakhir: {{ $methods[$default['type']] ?? $default['type'] }} {{ $default['account'] }}
                        </p>
                    @endif
                </aside>
            </header>

            <div class="wh-split">
                <section aria-labelledby="wh-history-title">
                    <div class="wh-section-head">
                        <h2 id="wh-history-title">Riwayat</h2>
                        <nav class="wh-filters" aria-label="Filter transaksi">
                            <a href="{{ route('wallet.index', ['filter' => 'all']) }}" class="{{ $filter === 'all' ? 'is-on' : '' }}">Semua</a>
                            <a href="{{ route('wallet.index', ['filter' => 'income']) }}" class="{{ $filter === 'income' ? 'is-on' : '' }}">Masuk</a>
                            <a href="{{ route('wallet.index', ['filter' => 'expense']) }}" class="{{ $filter === 'expense' ? 'is-on' : '' }}">Keluar</a>
                        </nav>
                    </div>

                    @if ($transactions->count() > 0)
                        <ul class="wh-tx">
                            @foreach ($transactions as $transaction)
                                @php
                                    $in = $transaction->isPositive();
                                    $statusLabel = match($transaction->status) {
                                        'completed' => 'Berhasil',
                                        'pending' => 'Memproses',
                                        'failed' => 'Gagal',
                                        default => $transaction->status,
                                    };
                                    $statusClass = match($transaction->status) {
                                        'pending' => 'is-pending',
                                        'failed' => 'is-failed',
                                        default => '',
                                    };
                                @endphp
                                <li>
                                    <span class="wh-dir {{ $in ? 'wh-dir-in' : 'wh-dir-out' }}">{{ $in ? 'In' : 'Out' }}</span>
                                    <div>
                                        <p class="wh-tx-title">{{ $transaction->description ?: $transaction->typeLabel() }}</p>
                                        <p class="wh-tx-meta">
                                            {{ $transaction->created_at->format('d M Y, H:i') }}
                                            @if ($transaction->reference_type === 'order' && $transaction->order)
                                                · #ORD-{{ sprintf('%05d', $transaction->order_id) }}
                                            @elseif ($transaction->reference_type === 'payout_request')
                                                · #WD-{{ sprintf('%05d', $transaction->reference_id) }}
                                            @endif
                                            · {{ $transaction->referenceLabel() }}
                                        </p>
                                    </div>
                                    <p class="wh-tx-amount {{ $in ? '' : 'is-out' }}">
                                        {{ $in ? '+' : '−' }}Rp{{ number_format(abs($transaction->amount), 0, ',', '.') }}
                                    </p>
                                    <span class="wh-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="wh-empty">
                            <h3>Belum ada pergerakan</h3>
                            @if ($filter === 'income')
                                <p>Pendapatan dari pesanan yang selesai akan tercatat di sini.</p>
                            @elseif ($filter === 'expense')
                                <p>Riwayat penarikan saldo akan tercatat di sini.</p>
                            @else
                                <p>Aktivitas saldo muncul setelah ada pendapatan dari pesanan atau penarikan.</p>
                            @endif
                        </div>
                    @endif
                </section>

                <aside class="wh-rail">
                    <h2>Penarikan terkini</h2>
                    @if ($withdrawals->count())
                        <ul class="wh-wd">
                            @foreach ($withdrawals as $wd)
                                <li>
                                    <strong>Rp{{ number_format($wd->amount, 0, ',', '.') }}</strong>
                                    <span>
                                        {{ $wd->methodLabel() }} · {{ $wd->created_at->format('d M Y') }}
                                        · {{ $wd->status }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Belum ada permintaan pencairan. Tarik saldo setelah mencapai minimum.</p>
                    @endif

                    <h2 style="margin-top:2rem;">Cara kerja</h2>
                    <p>Dana dari pesanan yang selesai masuk ke saldo ini. Penarikan diproses ke e-wallet atau rekening yang kamu daftarkan.</p>
                </aside>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/routes/wallet.js')
    @endpush
    
    <script src="{{ asset('js/wallet-ui.js') }}"></script>
</x-layouts.app>

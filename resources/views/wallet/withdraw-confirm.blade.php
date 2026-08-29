<x-layouts.app title="Konfirmasi Penarikan">
    @include('wallet._styles')

    <div class="wh-page">
        <div class="wh-inner">
            <a href="{{ route('wallet.index') }}" class="wh-back">Kembali ke dompet</a>

            <div class="wh-split-form" aria-hidden="true">
                <div>
                    <p class="wh-kicker">Pencairan</p>
                    <span class="wh-rule"></span>
                    <h1 class="wh-display" style="font-size: clamp(2.2rem, 5.5vw, 3.6rem);">Tarik<br>saldo</h1>
                    <p class="wh-lede">Tinjau permintaan penarikan sebelum dana dikirim ke akun tujuan.</p>
                    <p class="wh-balance-label" style="margin-top:2rem;">Saldo tersedia</p>
                    <div 
                        data-decrypted-balance
                        data-balance="{{ number_format($balance ?? 0, 0, ',', '.') }}"
                        data-currency="IDR"
                        class="wh-balance-value"
                        style="font-size:clamp(2rem,4.5vw,3.1rem);"
                    ></div>
                </div>
                <div class="wh-form">
                    <div class="wh-field">
                        <label>Jumlah penarikan</label>
                        <div class="wh-amount-wrap">
                            <span>Rp</span>
                            <input type="text" class="wh-input" readonly tabindex="-1" value="{{ number_format($payoutRequest->amount, 0, ',', '.') }}">
                        </div>
                    </div>
                    <div class="wh-field">
                        <label>Metode pencairan</label>
                        <input type="text" class="wh-input" readonly tabindex="-1" value="{{ $payoutRequest->methodLabel() }}">
                    </div>
                    <div class="wh-field">
                        <label>Nomor rekening / e-wallet</label>
                        <input type="text" class="wh-input" readonly tabindex="-1" value="{{ $payoutRequest->account_identifier }}">
                    </div>
                    <div class="wh-field">
                        <label>Nama pemilik</label>
                        <input type="text" class="wh-input" readonly tabindex="-1" value="{{ $payoutRequest->account_name }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <dialog id="wh-confirm-dialog" class="wh-dialog" data-server="1" aria-labelledby="wh-confirm-title">
        <div class="wh-dialog-body" id="result-card"
             data-wallet="{{ route('wallet.index') }}"
             data-retry="{{ route('wallet.withdraw.create') }}"
             data-amount="{{ $payoutRequest->amount }}"
             data-method="{{ $payoutRequest->methodLabel() }}"
             data-account="{{ $payoutRequest->account_identifier }}"
             data-ref="WD-{{ $payoutRequest->id }}">
            <h2 id="wh-confirm-title">Konfirmasi penarikan</h2>
            <p class="wh-dialog-copy">Pencairan akan diproses ke akun di bawah. Kamu masih bisa membatalkan selama hitungan berjalan.</p>
            <dl class="wh-rows">
                <div class="is-amount">
                    <dt>Jumlah</dt>
                    <dd>Rp{{ number_format($payoutRequest->amount, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt>Metode</dt>
                    <dd>{{ $payoutRequest->methodLabel() }}</dd>
                </div>
                <div>
                    <dt>Nomor</dt>
                    <dd>{{ $payoutRequest->account_identifier }}</dd>
                </div>
                <div>
                    <dt>Atas nama</dt>
                    <dd>{{ $payoutRequest->account_name }}</dd>
                </div>
            </dl>

            <div id="wh-process-panel">
                <div class="wh-progress">
                    <div class="wh-progress-track">
                        <div id="progress-bar" class="wh-progress-bar"
                             data-delay="{{ $processingDelay }}"
                             data-id="{{ $payoutRequest->id }}"></div>
                    </div>
                    <p id="countdown-text">Memproses dalam {{ $processingDelay }} detik</p>
                </div>
                <div id="processing-status" hidden>
                    <p>Sedang mengirim transfer</p>
                </div>
            </div>

            <div class="wh-dialog-actions" id="wh-confirm-actions">
                <button type="button" id="cancel-btn" class="wh-btn wh-btn-ghost"
                        data-cancel="{{ route('wallet.index') }}">Batalkan</button>
            </div>
        </div>
    </dialog>

    <script src="{{ asset('js/wallet-ui.js') }}"></script>
    <script src="{{ asset('js/payout-countdown.js') }}"></script>
</x-layouts.app>

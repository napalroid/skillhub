<x-layouts.app title="Penarikan Saldo">
    @include('wallet._styles')

    <div class="wh-page">
        <div class="wh-inner">
            <a href="{{ route('wallet.index') }}" class="wh-back">Kembali ke dompet</a>

            <div class="wh-split-form">
                <div>
                    <p class="wh-kicker">Pencairan</p>
                    <span class="wh-rule" aria-hidden="true"></span>
                    <h1 class="wh-display" style="font-size: clamp(2.2rem, 5.5vw, 3.6rem);">Tarik<br>saldo</h1>
                    <p class="wh-lede">Isi nominal dan tujuan. Kamu akan meninjau ringkasan sebelum permintaan diproses.</p>

                    <p class="wh-balance-label" style="margin-top:2rem;">Saldo tersedia</p>
                    <div 
                        data-decrypted-balance
                        data-balance="{{ number_format($balance, 0, ',', '.') }}"
                        data-currency="IDR"
                        class="wh-balance-value"
                        style="font-size:clamp(2rem,4.5vw,3.1rem);"
                    ></div>

                    <ul class="wh-note-list">
                        <li>Minimal penarikan <b>Rp{{ number_format($minAmount, 0, ',', '.') }}</b>. Tidak ada biaya admin.</li>
                        <li>Pastikan nomor dan nama pemilik sesuai akun tujuan. Kesalahan input tidak dapat dikembalikan.</li>
                        <li>Setelah dikonfirmasi, pencairan diproses dalam {{ config('payout.processing_delay_seconds', 10) }} detik.</li>
                    </ul>
                </div>

                <form id="withdraw-form" method="POST" action="{{ route('wallet.withdraw.store') }}" class="wh-form" data-server="true">
                    @csrf

                    <div class="wh-field">
                        <label for="amount">Jumlah penarikan</label>
                        <div class="wh-amount-wrap">
                            <span>Rp</span>
                            <input type="number" name="amount" id="amount" min="{{ $minAmount }}" step="1000"
                                   value="{{ old('amount') }}" placeholder="0" inputmode="numeric"
                                   class="wh-input @error('amount') is-invalid @enderror">
                        </div>
                        <p class="hint">Minimal Rp{{ number_format($minAmount, 0, ',', '.') }}. Maksimal sesuai saldo.</p>
                        @error('amount')
                            <p class="err" id="amount-err">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="wh-field">
                        <label for="method_type">Metode pencairan</label>
                        <select name="method_type" id="method_type" class="wh-select @error('method_type') is-invalid @enderror">
                            <option value="">Pilih metode</option>
                            @foreach ($methods as $value => $label)
                                <option value="{{ $value }}" @selected(old('method_type', $default['type']) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('method_type')
                            <p class="err">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="wh-field">
                        <label for="account_identifier">Nomor rekening / e-wallet</label>
                        <input type="text" name="account_identifier" id="account_identifier"
                               value="{{ old('account_identifier', $default['account']) }}"
                               placeholder="08xxxxxxxxxx / nomor rekening" autocomplete="off"
                               class="wh-input @error('account_identifier') is-invalid @enderror">
                        @error('account_identifier')
                            <p class="err">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="wh-field">
                        <label for="account_name">Nama pemilik</label>
                        <input type="text" name="account_name" id="account_name"
                               value="{{ old('account_name', $default['name']) }}"
                               placeholder="Sesuai rekening / e-wallet" autocomplete="name"
                               class="wh-input @error('account_name') is-invalid @enderror">
                        @error('account_name')
                            <p class="err">{{ $message }}</p>
                        @enderror
                    </div>

                    <dl class="wh-summary">
                        <div>
                            <dt>Biaya admin</dt>
                            <dd>Rp0</dd>
                        </div>
                        <div>
                            <dt>Diterima</dt>
                            <dd id="wh-net-preview">Sesuai nominal</dd>
                        </div>
                    </dl>

                     <button type="submit" id="wh-confirm-actions" class="wh-btn wh-btn-block">Lanjutkan penarikan</button>
                </form>
            </div>
        </div>
    </div>

    <dialog id="wh-confirm-dialog" class="wh-dialog" aria-labelledby="wh-confirm-title" data-delay="{{ config('payout.processing_delay_seconds', 10) }}">
        <div class="wh-dialog-body">
            <h2 id="wh-confirm-title">Konfirmasi penarikan</h2>
            <p class="wh-dialog-copy">Periksa tujuan dan nominal sebelum permintaan dikirim.</p>
            <dl class="wh-rows">
                <div class="is-amount">
                    <dt>Jumlah</dt>
                    <dd id="wh-preview-amount">—</dd>
                </div>
                <div>
                    <dt>Metode</dt>
                    <dd id="wh-preview-method">—</dd>
                </div>
                <div>
                    <dt>Nomor</dt>
                    <dd id="wh-preview-account">—</dd>
                </div>
                <div>
                    <dt>Atas nama</dt>
                    <dd id="wh-preview-name">—</dd>
                </div>
            </dl>
            <div class="wh-dialog-actions">
                <button type="button" id="wh-confirm-cancel" class="wh-btn wh-btn-ghost">Kembali</button>
                <button type="button" id="wh-confirm-submit" class="wh-btn">Konfirmasi</button>
            </div>
        </div>
    </dialog>

    <script src="{{ asset('js/wallet-ui.js') }}"></script>
    <script src="{{ asset('js/payout-countdown.js') }}"></script>
    <script>
        (function () {
            var amount = document.getElementById('amount');
            var net = document.getElementById('wh-net-preview');
            if (!amount || !net) return;
            amount.addEventListener('input', function () {
                var n = parseInt(String(amount.value).replace(/\D/g, ''), 10) || 0;
                net.textContent = n ? 'Rp' + n.toLocaleString('id-ID') : 'Sesuai nominal';
            });
        })();
    </script>
</x-layouts.app>

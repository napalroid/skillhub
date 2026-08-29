(function () {
    const hostId = 'wh-notice-host';
    const storageKey = 'wh-notice';

    function ensureHost() {
        let host = document.getElementById(hostId);
        if (host) return host;
        host = document.createElement('div');
        host.id = hostId;
        host.className = 'wh-notice-host';
        host.setAttribute('aria-live', 'polite');
        document.body.appendChild(host);
        return host;
    }

    window.whNotify = function (opts) {
        const host = ensureHost();
        const type = opts.type === 'error' ? 'error' : 'success';
        const el = document.createElement('div');
        el.className = 'wh-notice';
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');
        el.innerHTML =
            '<p class="wh-notice-kicker">' + (type === 'error' ? 'Failed' : 'Success') + '</p>' +
            '<p class="wh-notice-title"></p>' +
            (opts.message ? '<p class="wh-notice-msg"></p>' : '') +
            '<button type="button" class="wh-notice-close" aria-label="Tutup notifikasi">&times;</button>';
        el.querySelector('.wh-notice-title').textContent = opts.title || (type === 'error' ? 'Terjadi kesalahan' : 'Berhasil');
        const msg = el.querySelector('.wh-notice-msg');
        if (msg) msg.textContent = opts.message;
        host.appendChild(el);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { el.classList.add('is-in'); });
        });

        let closed = false;
        function dismiss() {
            if (closed) return;
            closed = true;
            el.classList.remove('is-in');
            el.classList.add('is-out');
            setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 240);
        }
        el.querySelector('.wh-notice-close').addEventListener('click', dismiss);
        if (type !== 'error') setTimeout(dismiss, 5600);
    };

    window.whNotifyPersist = function (opts) {
        try {
            sessionStorage.setItem(storageKey, JSON.stringify(opts));
        } catch (e) {}
    };

    document.addEventListener('DOMContentLoaded', function () {
        try {
            const stored = sessionStorage.getItem(storageKey);
            if (stored) {
                sessionStorage.removeItem(storageKey);
                window.whNotify(JSON.parse(stored));
            }
        } catch (e) {}

        document.querySelectorAll('main > .bg-green-100, main > .bg-red-100').forEach(function (banner) {
            const isError = banner.classList.contains('bg-red-100');
            const text = (banner.querySelector('span') || banner).textContent.trim();
            if (text) {
                window.whNotify({
                    type: isError ? 'error' : 'success',
                    title: isError ? 'Tidak dapat diproses' : 'Berhasil',
                    message: text,
                });
            }
            banner.remove();
        });

        const form = document.getElementById('withdraw-form');
        const dialog = document.getElementById('wh-confirm-dialog');
        if (!form || !dialog || !dialog.showModal) return;
        if (dialog.hasAttribute('data-server')) return;

        const amountInput = form.querySelector('[name="amount"]');
        const methodInput = form.querySelector('[name="method_type"]');
        const accountInput = form.querySelector('[name="account_identifier"]');
        const nameInput = form.querySelector('[name="account_name"]');
        const previewAmount = document.getElementById('wh-preview-amount');
        const previewMethod = document.getElementById('wh-preview-method');
        const previewAccount = document.getElementById('wh-preview-account');
        const previewName = document.getElementById('wh-preview-name');
        const confirmBtn = document.getElementById('wh-confirm-submit');
        const cancelBtn = document.getElementById('wh-confirm-cancel');

        function formatRp(value) {
            const n = parseInt(String(value).replace(/\D/g, ''), 10) || 0;
            return 'Rp' + n.toLocaleString('id-ID');
        }

        form.addEventListener('submit', function (event) {
            if (form.dataset.confirmed === '1') return;
            event.preventDefault();
            if (previewAmount) previewAmount.textContent = formatRp(amountInput && amountInput.value);
            if (previewMethod) {
                const opt = methodInput && methodInput.options[methodInput.selectedIndex];
                previewMethod.textContent = opt ? opt.text : '';
            }
            if (previewAccount) previewAccount.textContent = accountInput ? accountInput.value : '';
            if (previewName) previewName.textContent = nameInput ? nameInput.value : '';
            dialog.showModal();
            if (confirmBtn) confirmBtn.focus();
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                form.dataset.confirmed = '1';
                dialog.close();
                form.submit();
            });
        }
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function () { dialog.close(); });
        }
        dialog.addEventListener('close', function () {
            const trigger = form.querySelector('[type="submit"]');
            if (trigger) trigger.focus();
        });

        // Enhanced select animation
        document.querySelectorAll('.wh-select').forEach(function (select) {
            select.addEventListener('mousedown', function () {
                select.classList.add('wh-select-opening');
            });
            select.addEventListener('blur', function () {
                select.classList.remove('wh-select-opening');
            });
            select.addEventListener('change', function () {
                select.classList.remove('wh-select-opening');
                select.classList.add('wh-select-changed');
                setTimeout(function () {
                    select.classList.remove('wh-select-changed');
                }, 300);
            });
        });
    });
})();

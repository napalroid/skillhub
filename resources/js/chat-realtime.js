const chatRoot = document.getElementById('skillhub-chat');

if (chatRoot && window.Echo) {
    const list = chatRoot.querySelector('[data-message-list]');
    const form = chatRoot.querySelector('form');
    const input = chatRoot.querySelector('textarea');
    const submit = chatRoot.querySelector('button[type="submit"]');
    const currentUserId = Number(chatRoot.dataset.userId);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const scrollToLatest = () => { list.scrollTop = list.scrollHeight; };
    const append = (message) => {
        if (list.querySelector(`[data-message-id="${message.id}"]`)) return;
        const own = Number(message.sender_id) === currentUserId;
        const article = document.createElement('article');
        article.dataset.messageId = message.id;
        article.className = `chat-message ${own ? 'chat-message-own' : 'chat-message-other'}`;
        const name = document.createElement('span'); name.className = 'chat-message-name'; name.textContent = own ? 'Kamu' : message.sender_name;
        const body = document.createElement('p'); body.textContent = message.message;
        const time = document.createElement('time'); time.textContent = message.created_at;
        article.append(name, body, time); list.append(article); scrollToLatest();
    };
    const appendOffer = (offer) => {
        if (list.querySelector(`[data-offer-id="${offer.id}"]`)) return;
        const money = (amount) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(amount));
        const article = document.createElement('article');
        article.dataset.offerId = offer.id;
        article.className = 'my-2 border border-[#e5e5e5] bg-[#f6f6f6] p-4 text-black';
        const note = offer.note ? `<p class="mt-3 border-t border-[#e5e5e5] pt-3 text-xs leading-5 text-black/70"></p>` : '';
        const actions = chatRoot.dataset.isSeller === '0' ? `<div data-offer-actions class="mt-4 flex flex-wrap gap-2 border-t border-[#e5e5e5] pt-3"><form method="POST" action="/offers/${offer.id}/accept"><input type="hidden" name="_token" value="${csrf}"><button class="bg-black px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] text-white hover:bg-black/80">Terima</button></form><form method="POST" action="/offers/${offer.id}/reject"><input type="hidden" name="_token" value="${csrf}"><button class="border border-[#e5e5e5] bg-white px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] hover:bg-black hover:text-white">Tolak</button></form></div>` : '';
        article.innerHTML = `<div class="flex items-start justify-between gap-4"><div><p class="text-[10px] font-bold tracking-[.16em] text-black/55">PENAWARAN HARGA</p><h2 class="mt-1 text-sm font-extrabold uppercase">Penawaran baru</h2></div><span data-offer-status class="border border-[#e5e5e5] bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-[.04em]">${offer.status}</span></div><dl class="mt-4 grid grid-cols-2 gap-3 text-xs"><div><dt class="text-black/45">Harga asli</dt><dd class="mt-1 font-bold">${money(offer.original_price)}</dd></div><div><dt class="text-black/45">Harga kesepakatan</dt><dd class="mt-1 font-bold">${money(offer.offer_price)}</dd></div></dl>${note}${actions}`;
        if (offer.note) article.querySelector('p:last-child').textContent = offer.note;
        list.append(article);
        scrollToLatest();
    };

    scrollToLatest();
    window.Echo.private(`conversation.${chatRoot.dataset.conversationId}`).listen('.message.sent', (event) => append(event.message));
    window.Echo.private(`conversation.${chatRoot.dataset.conversationId}`).listen('.price-offer.created', (event) => appendOffer(event.offer));
    window.Echo.private(`conversation.${chatRoot.dataset.conversationId}`).listen('.price-offer.status-changed', (event) => {
        const offer = list.querySelector(`[data-offer-id="${event.offer.id}"]`);
        if (!offer) return;
        const status = offer.querySelector('[data-offer-status]');
        if (status) status.textContent = event.offer.status;
        offer.querySelector('[data-offer-actions]')?.remove();
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;
        submit.disabled = true;
        try {
            const response = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Socket-ID': window.Echo.socketId() }, body: JSON.stringify({ message }) });
            if (!response.ok) throw new Error('Pesan tidak dapat dikirim.');
            const payload = await response.json(); append(payload.message); input.value = ''; input.focus();
        } catch (error) { chatRoot.querySelector('[data-chat-error]').textContent = error.message; }
        finally { submit.disabled = false; }
    });
}

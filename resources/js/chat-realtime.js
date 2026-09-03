document.addEventListener('DOMContentLoaded', () => {
    const chatRoot = document.getElementById('skillhub-chat');
    
    if (!chatRoot) {
        console.log('[Chat] Chat root element not found');
        return;
    }
    
    const list = chatRoot.querySelector('[data-message-list]');
    const messageForm = chatRoot.querySelector('[data-chat-form]');
    const offerFormAccept = chatRoot.querySelector('form[action*="/offers/accept"]');
    const offerFormReject = chatRoot.querySelector('form[action*="/offers/reject"]');
    const input = chatRoot.querySelector('[data-chat-input]');
    const submit = chatRoot.querySelector('[data-chat-submit]');
    const currentUserId = Number(chatRoot.dataset.userId);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    
    console.log('[Chat] Elements found:', { 
        chatRoot: !!chatRoot, 
        messageForm: !!messageForm, 
        input: !!input, 
        submit: !!submit,
        csrf: !!csrf 
    });
    
    if (!csrf) {
        console.error('[Chat] CSRF token not found!');
        return;
    }
    
    if (!messageForm) {
        console.error('[Chat] Message form not found!');
        return;
    }
    
    console.log('[Chat] Chat initialized for conversation:', chatRoot.dataset.conversationId);
    
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

    scrollToLatest();
    
    if (window.Echo && window.Echo.private) {
        try {
            const channel = window.Echo.private(`conversation.${chatRoot.dataset.conversationId}`);
            
            channel.listen('.message.sent', (event) => append(event.message));
            
            channel.listen('.price-offer.created', (event) => {
                console.log('[Chat] New offer received:', event);
                const offer = event.offer;
                const article = document.createElement('article');
                article.dataset.offerId = offer.id;
                article.className = 'my-2 border border-[#e5e5e5] bg-[#f6f6f6] p-4 text-black';
                article.innerHTML = `
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="text-[10px] font-bold uppercase tracking-[.16em] text-black/55">Penawaran harga</p><h2 class="mt-1 text-sm font-extrabold uppercase">Jasa</h2></div>
                        <span data-offer-status class="border border-[#e5e5e5] bg-white px-2 py-1 text-[10px] font-bold uppercase tracking-[.04em]">${offer.status}</span>
                    </div>
                    <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div><dt class="text-black/45">Harga asli</dt><dd class="mt-1 font-bold">Rp${Number(offer.original_price).toLocaleString('id-ID')}</dd></div>
                        <div><dt class="text-black/45">Harga kesepakatan</dt><dd class="mt-1 font-bold">Rp${Number(offer.offer_price).toLocaleString('id-ID')}</dd></div>
                    </dl>
                    ${offer.note ? `<p class="mt-3 border-t border-[#e5e5e5] pt-3 text-xs leading-5 text-black/70">${offer.note}</p>` : ''}
                    ${offer.status === 'pending' && chatRoot.dataset.isSeller === '0' ? `
                        <div data-offer-actions class="mt-4 flex flex-wrap gap-2 border-t border-[#e5e5e5] pt-3">
                            <form method="POST" action="/offers/${offer.id}/accept"><input type="hidden" name="_token" value="${csrf}"><button class="bg-black px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] text-white hover:bg-black/80">Terima</button></form>
                            <form method="POST" action="/offers/${offer.id}/reject"><input type="hidden" name="_token" value="${csrf}"><button class="border border-[#e5e5e5] bg-white px-3 py-2 text-[11px] font-bold uppercase tracking-[.06em] hover:bg-black hover:text-white">Tolak</button></form>
                        </div>
                    ` : ''}
                `;
                list.append(article);
                scrollToLatest();
            });
            
            channel.listen('.price-offer.status-changed', (event) => {
                console.log('[Chat] Offer status changed:', event);
                const offer = event.offer;
                const offerEl = list.querySelector(`[data-offer-id="${offer.id}"]`);
                if (offerEl) {
                    offerEl.querySelector('[data-offer-status]').textContent = offer.status;
                    offerEl.querySelector('[data-offer-actions]')?.remove();
                }
            });
        } catch (err) {
            console.warn('[Chat] WebSocket error:', err.message);
        }
    }

    // Handle form kirim pesan saja
    if (messageForm && submit) {
        console.log('[Chat] Attaching click listener to submit button');
        
        const sendMessage = async () => {
            console.log('[Chat] Send message triggered');
            const message = input.value.trim();
            if (!message) return;
            submit.disabled = true;
            
            const errorEl = chatRoot.querySelector('[data-chat-error]');
            if (errorEl) errorEl.textContent = '';
            
            try {
                const socketId = (window.Echo && typeof window.Echo.socketId === 'function') 
                    ? window.Echo.socketId() 
                    : null;
                
                const response = await fetch(messageForm.action, { 
                    method: 'POST', 
                    headers: { 
                        'Accept': 'application/json', 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrf,
                        ...(socketId ? { 'X-Socket-ID': socketId } : {})
                    }, 
                    body: JSON.stringify({ message }) 
                });
                
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Error ${response.status}`);
                }
                
                const payload = await response.json();
                console.log('[Chat] Server response:', payload);
                append(payload.message);
                input.value = '';
                input.focus();
                console.log('[Chat] Message sent successfully');
            } catch (error) {
                console.error('[Chat] Error:', error);
                if (errorEl) errorEl.textContent = error.message || 'Pesan tidak dapat dikirim.';
            } finally {
                submit.disabled = false;
            }
        };
        
        submit.addEventListener('click', sendMessage);
        
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        console.log('[Chat] Event listeners attached successfully');
    }

    // Handle tombol "Terima"
    if (offerFormAccept) {
        offerFormAccept.addEventListener('submit', async (event) => {
            event.preventDefault();
            const btn = offerFormAccept.querySelector('button');
            btn.disabled = true;
            
            try {
                const socketId = (window.Echo && typeof window.Echo.socketId === 'function') 
                    ? window.Echo.socketId() 
                    : null;
                
                const response = await fetch(offerFormAccept.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        ...(socketId ? { 'X-Socket-ID': socketId } : {})
                    }
                });
                
                if (!response.ok) throw new Error('Gagal menerima penawaran');
                
                const data = await response.json();
                console.log('[Offer] Accepted');
                
                const offerEl = offerFormAccept.closest('[data-offer-id]');
                if (offerEl) {
                    offerEl.querySelector('[data-offer-status]').textContent = 'Accepted';
                    offerEl.querySelector('[data-offer-actions]')?.remove();
                }
                
                window.location.href = '/orders/' + data.order_id + '/payment';
                
            } catch (error) {
                alert(error.message);
                btn.disabled = false;
            }
        });
    }

    // Handle tombol "Tolak"
    if (offerFormReject) {
        offerFormReject.addEventListener('submit', async (event) => {
            event.preventDefault();
            const btn = offerFormReject.querySelector('button');
            btn.disabled = true;
            
            try {
                const socketId = (window.Echo && typeof window.Echo.socketId === 'function') 
                    ? window.Echo.socketId() 
                    : null;
                
                const response = await fetch(offerFormReject.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        ...(socketId ? { 'X-Socket-ID': socketId } : {})
                    }
                });
                
                if (!response.ok) throw new Error('Gagal menolak penawaran');
                
                const data = await response.json();
                console.log('[Offer] Rejected');
                
                const offerEl = offerFormReject.closest('[data-offer-id]');
                if (offerEl) {
                    offerEl.querySelector('[data-offer-status]').textContent = 'Rejected';
                    offerEl.querySelector('[data-offer-actions]')?.remove();
                }
                
            } catch (error) {
                alert(error.message);
                btn.disabled = false;
            }
        });
    }
});

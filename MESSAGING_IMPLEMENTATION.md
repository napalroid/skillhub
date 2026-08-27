# SkillHub Messaging System Implementation — Complete

## Overview
Implemented enhanced `/messages` (buyer) and `/seller/messages` (seller) pages for SkillHub marketplace with modern design, real-time messaging, and responsive UX.

## Architecture Summary

### Database Layer
- **Conversations**: Links buyer + seller around a specific service
- **Messages**: Text-only, with `read_at` timestamp for read receipts
- **Price Offers**: Negotiation layer tied to conversations
- **User Notifications**: Polymorphic notification log

### Real-Time Infrastructure
- **Technology**: Laravel Reverb + Laravel Echo + Pusher.js
- **Private Channels**: `conversation.{id}` per conversation
- **Events**:
  - `.message.sent` → new message broadcast
  - `.price-offer.created` → offer created
  - `.price-offer.status-changed` → offer status updated
- **Client Handling**: `resources/js/chat-realtime.js` auto-appends messages, deduplicates by message ID

### Authentication & Authorization
- Middleware: `auth` required on all conversation routes
- Authorization: `Conversation::hasParticipant($user)` validates access
- Role Detection: `$isSeller = $conversation->seller_id === auth()->id()`

---

## Implementation Details

### 1. `/messages` (Buyer View)
**Route**: `GET /messages` → `ConversationController@index()`

**Features**:
- List conversations where user is `buyer_id`
- Unread count per conversation (messages from seller with `read_at = null`)
- Search by partner name, service title, or message content
- Filter: All / Unread / Read
- Pagination: 15 per page
- Mobile-responsive: sidebar on desktop, full-screen chat on mobile
- Staggered menu + header matching landing page design

**Backend Logic** (ConversationController):
```php
$conversations = Conversation::where('buyer_id', auth()->id())
    ->with(['service', 'buyer', 'seller', 'latestMessage'])
    ->withCount(['messages as unread_count' => fn($q) => 
        $q->where('sender_id', '!=', auth()->id())->whereNull('read_at')])
    ->latest('updated_at')
    ->paginate(15);
```

---

### 2. `/seller/messages` (Seller View)
**Route**: `GET /seller/messages` → `ConversationController@sellerIndex()`

**Features**:
- Identical to buyer view, but filters `seller_id = auth()->id()`
- Seller can create price offers (buyer negotiates)
- Same search, filter, pagination as buyer view

---

### 3. Conversation Detail: `/messages/{conversation}`
**Route**: `GET /messages/{conversation}` → `ConversationController@show()`

**Features**:
- Timeline merges messages + price offers, sorted by `created_at`
- Auto-marks messages as `read` on page load (server-side):
  ```php
  $conversation->messages()
      ->where('sender_id', '!=', auth()->id())
      ->whereNull('read_at')
      ->update(['read_at' => now()]);
  ```
- Real-time message append via Reverb broadcast
- Message send form posts to `POST /messages/{conversation}` (JSON response)
- Seller sees "Buat Penawaran" button
- Buyer sees accept/reject buttons on pending offers

---

### 4. Send Message
**Route**: `POST /messages/{conversation}` → `ConversationController@store()`

**Flow**:
1. Validate: `message` required, max 1500 chars
2. Create message, mark read_at = null (unread by recipient)
3. Broadcast `MessageSent` event to private channel
4. Create `UserNotification` for recipient
5. Update conversation `updated_at` (floats to top of list)
6. Return JSON with message data for optimistic UI

**Error Handling**:
- Broadcast failures logged but don't fail the request
- Message already safely persisted before broadcast attempt
- Client shows error message if HTTP request fails

---

### 5. Price Offers
**Seller Creates**: `POST /conversations/{conversation}/offers`
- Service: `PriceOfferService::create()`
- Cancels previous pending offers, sets 24h expiry
- Broadcasts `PriceOfferCreated` event

**Buyer Accepts**: `POST /offers/{priceOffer}/accept`
- Creates `Order` with `status = 'menunggu_pembayaran'`
- Broadcasts `PriceOfferStatusChanged`
- Redirects to payment page

**Buyer Rejects**: `POST /offers/{priceOffer}/reject`
- Sets status to 'rejected'
- Broadcasts `PriceOfferStatusChanged`
- Returns to conversation

---

## UI/UX Design

### Design Language
- **Palette**: Adidas-inspired monochrome (black, white, grays)
- **Typography**: DM Sans (existing project font)
- **Spacing**: Generous whitespace, minimal borders (1px subtle gray)
- **Header**: Matches landing page (staggered menu, profile dropdown, notifications)
- **Footer**: Consistent with existing pages

### Conversation List
```
[Header: Staggered Menu + Profile]

[Conversation List]          [Chat Area]
├─ Search input              ├─ Header (partner, service, offers button)
├─ Filters: All/Unread/Read  ├─ Timeline
├─ [Item 1]                  │  ├─ Message (own/other)
│  ├─ Partner Name + Badge   │  ├─ Message + timestamp
│  ├─ Service title          │  └─ Offer card (if negotiating)
│  ├─ Last message preview   │
│  └─ Timestamp + Unread     ├─ Message input
├─ [Item 2]                  └─ Error display
└─ ...
```

### Responsive Behavior
- **Desktop** (lg+): 2-column layout (280px sidebar + 1fr chat)
- **Tablet** (md): Stacked, scrollable sidebar
- **Mobile** (sm): Full-screen chat, back button to list

---

## Real-Time Flow

### Sender Perspective
1. User types message, clicks Send
2. Message optimistically appended to DOM (pending state)
3. XHR POST to backend (includes `X-Socket-ID` to skip self)
4. Backend creates message, broadcasts to other participants
5. Response returns message with `id`, DOM updates (no duplicate)

### Recipient Perspective
1. User viewing chat, listening on private channel
2. Event received: `.message.sent`
3. `append()` creates DOM node, deduplicates by `data-message-id`
4. Automatically scrolls to latest if already at bottom
5. Conversation list re-orders (latest message floats to top on next refresh)

### Notification
1. Message created, recipient receives `UserNotification`
2. Notification bell badge updates (if recipient's browser has it open)
3. If recipient not on chat page, they see notification on next visit

---

## Security & Authorization

### Route Protection
```php
Route::middleware(['auth'])->group(function() {
    Route::get('/messages', [ConversationController::class, 'index']);
    Route::get('/messages/{conversation}', [ConversationController::class, 'show']);
    Route::post('/messages/{conversation}', [ConversationController::class, 'store']);
    // ... etc
});
```

### Authorization Check
```php
private function authorizeParticipant(Conversation $conversation): void {
    abort_unless($conversation->hasParticipant(auth()->user()), 403);
}
```

### Channel Authorization
```php
// routes/channels.php
Broadcast::channel('conversation.{conversation}', function ($user, Conversation $conversation) {
    return $conversation->hasParticipant($user);
});
```

---

## Performance Optimizations

### Query Optimization
- Eager load: `service, buyer, seller, latestMessage`
- Count: `withCount(['messages as unread_count' => ...])`
- Index on `(conversation_id, created_at)` for message ordering

### Pagination
- Conversation list: 15 per page
- Message history: Load all (ponytail: consider cursor pagination for 1000+ message chats)

### Real-Time Efficiency
- Broadcast failure doesn't block message save
- Deduplication prevents duplicate DOM nodes
- Socket ID prevents echo to sender
- Single Echo connection per page (not per component)

---

## File Changes

### Blade Views
- `resources/views/conversations/index.blade.php` — NEW: conversation list with search, filter, responsive layout
- `resources/views/conversations/show.blade.php` — UPDATED: premium design, Alpine modal for offers

### Controllers
- `app/Http/Controllers/ConversationController.php` — UPDATED: added filter/search logic in `list()` method

### JavaScript
- `resources/js/chat-realtime.js` — UPDATED: CSS class names to match new design (`message-own`, `message-other`, etc.)

### Already Existing (Unchanged)
- `app/Models/Conversation.php`
- `app/Models/Message.php`
- `app/Models/User.php` (has `buyerConversations()`, `sellerConversations()` relations)
- `app/Events/MessageSent.php`
- `app/Events/PriceOfferCreated.php`
- `app/Events/PriceOfferStatusChanged.php`
- `resources/js/echo.js` (Reverb setup)
- `routes/channels.php` (private channel auth)

---

## Testing Checklist

### Buyer Flow
- [ ] Visit `/messages` → see all conversations
- [ ] Filter "Belum Dibaca" → see only unread
- [ ] Search by partner name → results update
- [ ] Click conversation → chat detail loads
- [ ] Send message → appears in chat, realtime if other browser open
- [ ] Messages marked as read on page load
- [ ] Return to list → conversation re-ordered to top

### Seller Flow
- [ ] Visit `/seller/messages` → see seller-side conversations
- [ ] Create price offer → appears in timeline
- [ ] Buyer accepts → order created, redirects to payment
- [ ] Buyer rejects → status updates realtime
- [ ] Send message → buyer receives notification

### Realtime (Open 2 browser tabs)
- [ ] Tab 1 sends message
- [ ] Tab 2 receives message without refresh
- [ ] Tab 1 sees message appear
- [ ] Conversation list re-orders on both tabs (next visit or with polling)

### Responsive
- [ ] Desktop: 2-column layout
- [ ] Mobile: Full-screen chat, back button works
- [ ] Input not hidden by mobile keyboard
- [ ] Conversation list scrollable

### Authorization
- [ ] User A cannot access User B's conversations
- [ ] User A cannot guess conversation ID and access
- [ ] Broadcast only sent to participants

---

## Known Limitations

1. **Message History Pagination**: All messages loaded at once. For 1000+ message chats, consider cursor-based pagination.
2. **Conversation List Re-ordering**: Requires page refresh to see latest message float to top. Consider polling `/messages?filter=all` on interval.
3. **Typing Indicators**: Not implemented. Requires additional event type.
4. **Online Status**: Not implemented. Requires presence tracking.
5. **Message Edit/Delete**: Not implemented. Messages are permanent.
6. **File Attachments**: Not supported. Messages are text-only.
7. **Read Receipts**: Implicit (read_at), not shown to sender.

---

## Future Enhancements

### High Priority
- Cursor-based message pagination (load older messages on scroll up)
- Conversation list auto-refresh or polling to float latest to top
- Message search within conversation
- Unread count in page title badge

### Medium Priority
- Typing indicators (`.user.typing` event)
- Online status presence
- Message reactions (emoji)
- Conversation pinning/archiving
- Bulk mark-as-read

### Low Priority
- Voice/video call integration
- Message edit/delete
- File attachments
- Custom typing animation
- Message forwarding

---

## Deployment Notes

### Environment Variables
Ensure Reverb is configured in `.env`:
```
BROADCAST_CONNECTION=reverb
REVERB_APP_KEY=...
REVERB_APP_SECRET=...
REVERB_HOST=...
REVERB_PORT=...
```

### Build & Serve
```bash
npm run build          # Compile assets
php artisan serve      # Start dev server
php artisan reverb:start  # Start WebSocket server (separate terminal)
```

### Production
- Use `npm run build` (not `dev`)
- Ensure Reverb server is running (systemd/supervisor)
- Set `REVERB_SCHEME=https` if using SSL
- Monitor WebSocket connections

---

## Summary

SkillHub messaging now offers:
✅ Real-time message delivery (buyer ↔ seller)
✅ Price offer negotiation with timeline
✅ Unread/read state tracking
✅ Search & filter conversations
✅ Premium design (Adidas-inspired monochrome)
✅ Responsive layout (desktop, mobile, tablet)
✅ Secure authorization per participant
✅ Optimized queries with eager loading
✅ Graceful error handling
✅ Broadcast resilience (messages persist even if WebSocket fails)

Implementation complete. Ready for testing and deployment.

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - SkillHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --adidas-line: #e5e5e5; }
        body { font-family: 'Inter', sans-serif; background: #f6f6f6; color: #080808; }
        .font-display { font-family: 'Archivo', sans-serif; }
        .chat-message { max-width: 76%; padding: .8rem 1rem; border: 1px solid var(--adidas-line); }
        .chat-message-own { margin-left: auto; background: #080808; color: #fff; border-color: #080808; }
        .chat-message-other { background: #fff; color: #080808; }
        .chat-message-name, .chat-message time { display: block; font-size: .68rem; font-weight: 700; letter-spacing: .04em; opacity: .6; text-transform: uppercase; }
        .chat-message p { margin: .3rem 0; font-size: .88rem; line-height: 1.5; white-space: pre-wrap; }
        [x-cloak] { display: none !important; }
    </style>
    @vite('resources/js/app.js')
</head>
<body class="">
    <div id="skillhub-staggered-menu"
         data-home="{{ route('home') }}"
         data-marketplace="{{ route('services.index') }}"
         data-chat="{{ route('conversations.seller-index') }}"
         data-login="{{ route('login') }}"
         data-register="{{ route('register') }}"
         data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
         data-user-name="{{ auth()->user()?->name ?? '' }}"
         data-avatar-url="{{ auth()->user()?->avatar_url ?? '' }}"
         data-profile-url="{{ route('profile.edit') }}"
         data-logout-url="{{ route('logout') }}"
         data-notifications-url="{{ auth()->check() ? route('notifications.index') : '' }}"
         data-notifications-read-all-url="{{ auth()->check() ? route('notifications.read-all') : '' }}"
          data-dompet="{{ route('wallet.index') }}"
          data-pesanan="{{ route('orders.index') }}"
          data-csrf-token="{{ csrf_token() }}"
          data-is-admin="{{ auth()->check() && auth()->user()->isAdmin() ? 'true' : 'false' }}"
          data-admin-dashboard="{{ auth()->check() && auth()->user()->isAdmin() ? route('admin.dashboard') : '' }}"></div>
    <script id="skillhub-account-notifications-data" type="application/json">@json($accountNotifications ?? collect(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)</script>
    <main x-data="{ offerModal: false, mobileView: '{{ $conversation ? 'chat' : 'list' }}' }" class="flex min-h-screen flex-col bg-white">
        <div class="grid min-h-0 flex-1 lg:grid-cols-[360px_1fr]">
            <aside class="min-h-0 border-b border-[#e5e5e5] lg:border-b-0 lg:border-r"
                   :class="mobileView === 'chat' ? 'hidden lg:flex' : 'flex'">
                @include('conversations._sidebar')
            </aside>

            <div class="min-h-0 flex flex-col"
                 :class="mobileView === 'list' ? 'hidden lg:flex' : 'flex'">
                @include('conversations._chat')
            </div>
        </div>
    </main>
    
    <x-site-footer />
</body>
</html>

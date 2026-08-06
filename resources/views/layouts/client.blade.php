<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @laravelPWA
    <style>
        :root { --primary: #07fb03; }
        .text-primary { color: #20fb03; }
        .bg-primary { background-color: #28fb03; }
        .border-primary { border-color: #03fb03; }
        .client-section {
            border: 1px solid #20fb03 !important;
            box-shadow: inset 0 0 12px rgba(32, 251, 3, 0.28);
            isolation: isolate;
            overflow: hidden;
            position: relative;
        }
        .client-section::before {
            background: radial-gradient(ellipse at center, rgba(32, 251, 3, 0.14) 0%, rgba(32, 251, 3, 0.06) 55%, rgba(32, 251, 3, 0.02) 100%);
            content: '';
            inset: 0;
            pointer-events: none;
            position: absolute;
            z-index: 0;
        }
        .client-section > * {
            position: relative;
            z-index: 1;
        }
        .activity-notification {
            left: 50%;
            max-width: calc(100vw - 2rem);
            opacity: 0;
            pointer-events: none;
            position: fixed;
            top: 4.5rem;
            transform: translate(-50%, -3rem);
            transition: opacity 300ms ease, transform 700ms ease-out;
            width: 22rem;
            z-index: 60;
        }
        .activity-notification.is-visible {
            opacity: 1;
            transform: translate(-50%, 0);
        }
        .activity-notification.is-hiding {
            opacity: 0;
            transform: translate(-50%, 0);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900">

    <!-- Header -->
    <header class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-lg font-bold text-primary">EverGreen</h1>
            </div>
            <div class="flex items-center gap-4">
                {{-- Notification Bell --}}
                <a href="{{ route('client.notifications.index') }}" class="relative">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">
                            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </a>
                {{-- Profile --}}
                <a href="{{ route('client.profile.index') }}">
                    <svg class="w-6 h-6 text-gray-400 hover:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Simulated activity ticker --}}
    <div id="activity-notification" class="activity-notification flex items-center gap-2 rounded-lg bg-[#20fb03] px-3 py-2 text-xs shadow-sm" role="status" aria-live="polite">
            <svg class="w-3 h-3 shrink-0 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm0 16a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
            </svg>
            <span id="activity-ticker-message" class="text-white truncate">
                Withdrawal approved for 097000***
            </span>
        </div>

    <!-- Main Content -->
    <main class="bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-4 pb-20">

            @if(session('success'))
                <div class="bg-gray-800 border border-primary text-primary px-4 py-2 rounded-lg mb-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-gray-800 border border-red-500 text-red-400 px-4 py-2 rounded-lg mb-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-800 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-around items-center py-2">

                {{-- Home --}}
                <a href="{{ route('client.dashboard') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.dashboard') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-[10px]">Home</span>
                </a>

                {{-- VIPs --}}
                <a href="{{ route('client.vips.index') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.vips.index') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 3 3 5 3 5s3-2 3-5c0-1.657-1.343-3-3-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414M16.95 16.95l1.414 1.414M7.05 7.05L5.636 5.636"/>
                    </svg>
                    <span class="text-[10px]">VIPs</span>
                </a>

                {{-- Mes VIPs --}}
                <a href="{{ route('client.vips.mine') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.vips.mine') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3"/>
                    </svg>
                    <span class="text-[10px]">My VIPs</span>
                </a>

                {{-- Team --}}
                <a href="{{ route('client.referrals.index') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.referrals.*') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-[10px]">Team</span>
                </a>

                {{-- Profile --}}
                <a href="{{ route('client.profile.index') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.profile.*') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px]">Profile</span>
                </a>

            </div>
        </div>
    </nav>

    @stack('scripts')
    <script>
        (function () {
            const notification = document.getElementById('activity-notification');
            const message = document.getElementById('activity-ticker-message');
            const zambianPrefixes = ['095', '096', '097', '076', '077'];

            function maskedZambianNumber() {
                const prefix = zambianPrefixes[Math.floor(Math.random() * zambianPrefixes.length)];
                const suffix = String(Math.floor(Math.random() * 10000000)).padStart(7, '0');
                return (prefix + suffix).slice(0, 6) + '***';
            }

            function refreshTicker() {
                message.textContent = 'Withdrawal approved for ' + maskedZambianNumber();
                notification.classList.remove('is-hiding');
                notification.classList.add('is-visible');
                window.setTimeout(function () {
                    notification.classList.add('is-hiding');
                }, 3000);
                window.setTimeout(function () {
                    notification.classList.remove('is-visible', 'is-hiding');
                }, 3300);
            }

            refreshTicker();
            window.setInterval(refreshTicker, 45000);
        }());
    </script>
</body>
</html>

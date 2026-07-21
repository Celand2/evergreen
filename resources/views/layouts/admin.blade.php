<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EverGreen Admin</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Conteneur global : hauteur écran fixe, pas de scroll global -->
    <div class="h-screen overflow-hidden flex">

        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden" onclick="toggleAdminSidebar(false)"></div>

        <!-- Sidebar : overlay fixe sur mobile, colonne normale (flex) sur desktop -->
        <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-200 ease-in-out
            md:static md:translate-x-0 md:z-auto md:shadow-none md:w-64 md:shrink-0 shadow-xl overflow-y-auto">
            <div class="p-4 border-b border-gray-800 md:border-none">
                <h1 class="text-2xl font-bold">EverGreen Admin</h1>
            </div>
            <nav class="mt-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">📊</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.vips.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.vips.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">⭐</span>
                    <span>VIPs</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">👥</span>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.deposits.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.deposits.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">💰</span>
                    <span>Deposits</span>
                </a>
                <a href="{{ route('admin.withdrawals.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.withdrawals.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">💸</span>
                    <span>Withdrawals</span>
                </a>
                <a href="{{ route('admin.payment-methods.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.payment-methods.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">💳</span>
                    <span>Payment Methods</span>
                </a>
                <a href="{{ route('admin.exchange-rates.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.exchange-rates.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">💱</span>
                    <span>Exchange Rates</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.notifications.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">🔔</span>
                    <span>Notifications</span>
                </a>
                <a href="{{ route('admin.referrals.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.referrals.*') ? 'bg-gray-800 border-l-4 border-green-500' : '' }}">
                    <span class="mr-3">👥</span>
                    <span>Referrals</span>
                </a>
            </nav>
        </aside>

        <!-- Colonne de droite : header + main, dans le flux normal (plus de fixed/pt hack) -->
        <div class="flex-1 flex flex-col h-full min-w-0">

            <!-- Header : simple élément en haut de la colonne, touche toujours la sidebar et le haut -->
            <header class="shrink-0 z-20 bg-white shadow-sm">
                <div class="flex flex-wrap justify-between items-center gap-3 px-6 py-4">
                    <div class="flex items-center gap-3">
                        <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200" onclick="toggleAdminSidebar(true)" aria-label="Open sidebar">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold">@yield('title', 'Dashboard')</h2>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-gray-700 hover:text-gray-900">
                            <span class="mr-2">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main : seule cette zone scrolle (verticalement). min-w-0 empêche le contenu (tables) de forcer l'élargissement de toute la page -->
            <main class="flex-1 min-w-0 bg-gray-100 px-6 py-6 overflow-y-auto overflow-x-hidden">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleAdminSidebar(open) {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const shouldOpen = typeof open === 'boolean' ? open : sidebar.classList.contains('-translate-x-full');

            if (shouldOpen) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    </script>
</body>
</html>
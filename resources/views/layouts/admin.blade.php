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
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-4">
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

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold">@yield('title', 'Dashboard')</h2>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center text-gray-700 hover:text-gray-900">
                            <span class="mr-2">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-auto p-6">
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
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --primary: #07fb03; }
        .text-primary { color: #20fb03; }
        .bg-primary { background-color: #28fb03; }
        .border-primary { border-color: #03fb03; }
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

                {{-- Invest --}}
                <a href="{{ route('client.vips.index') }}" class="flex flex-col items-center gap-0.5 p-2 {{ request()->routeIs('client.vips.*') ? 'text-primary' : 'text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span class="text-[10px]">Invest</span>
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
</body>
</html>
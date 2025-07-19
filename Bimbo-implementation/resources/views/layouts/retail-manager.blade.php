<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <!-- Sidebar Navigation -->
    <aside class="fixed top-0 left-0 h-full w-56 bg-gradient-to-b from-indigo-100 via-blue-100 to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-lg z-30 flex flex-col">
        <!-- Removed duplicated logo/title -->
        <div class="h-16 px-4 border-b border-gray-200 dark:border-gray-700"></div>
        <nav class="flex-1 flex flex-col py-4 space-y-2">
            <a href="{{ route('retail.orders.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ request()->routeIs('retail.orders.*') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Bread Orders
            </a>
            <a href="{{ route('retail.inventory.check') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ request()->routeIs('retail.inventory.check') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4"/></svg>
                Check Inventory
            </a>
            <a href="{{ route('retail.forecast.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ request()->routeIs('retail.forecast.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Demand Forecast
            </a>
            <a href="{{ route('retail.chat.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ request()->routeIs('retail.chat.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2m4-4h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                Chat with Suppliers
            </a>
            <a href="{{ route('retail.products.index') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition {{ request()->routeIs('retail.products.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                Bread Products
            </a>
            @yield('navigation-links')
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 mt-2 rounded-lg bg-red-500 text-white font-semibold shadow hover:bg-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>
        <div class="px-4 pb-4">
            <button id="dark-mode-toggle" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-indigo-200 dark:bg-gray-700 text-indigo-800 dark:text-indigo-100 font-semibold shadow hover:bg-indigo-300 dark:hover:bg-gray-600 transition">
                <svg id="dark-mode-icon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path id="sun-icon" class="block dark:hidden" stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-12.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 4.66l-.71-.71M4.05 4.05l-.71-.71M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    <path id="moon-icon" class="hidden dark:block" stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                </svg>
                <span class="block dark:hidden">Dark Mode</span>
                <span class="hidden dark:block">Light Mode</span>
            </button>
        </div>
    </aside>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-green-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 ml-56">
        <!-- Top Navigation Bar -->
        <nav class="sticky top-0 z-40 bg-white shadow flex items-center justify-between px-8 h-16 w-full">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img class="h-10 w-auto rounded shadow" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                    <span class="font-bold text-xl text-indigo-700 tracking-tight hidden sm:inline">Bimbo Retail</span>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Dashboard</a>
                <a href="{{ route('retail.orders.index') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Orders</a>
                <a href="{{ route('retail.inventory.check') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Inventory</a>
                <a href="{{ route('retail.forecast.index') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Forecast</a>
                <a href="{{ route('retail.products.index') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Products</a>
                <a href="{{ route('retail.chat.index') }}" class="text-gray-700 hover:text-blue-700 font-semibold transition">Chat</a>
            </div>
            <div class="relative group">
                <button class="flex items-center gap-2 text-gray-700 hover:text-blue-700 font-semibold focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>{{ Auth::user()->name ?? 'Account' }}</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg py-2 opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition-opacity duration-150 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Log Out</button>
                    </form>
                </div>
            </div>
        </nav>
        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @yield('header')
                </h2>
            </div>
        </header>
        <!-- Page Content -->
        <main>
            @hasSection('fullpage')
                @yield('fullpage')
            @else
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
    <script>
        // Hamburger menu toggle
        document.getElementById('nav-toggle')?.addEventListener('click', function() {
            var menu = document.getElementById('nav-menu');
            menu?.classList.toggle('hidden');
        });
        document.getElementById('user-menu-button')?.addEventListener('click', function() {
            document.getElementById('user-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>

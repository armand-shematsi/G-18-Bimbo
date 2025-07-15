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
        <div class="flex items-center gap-2 h-16 px-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <img class="h-10 w-auto rounded shadow" src="{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                <span class="font-bold text-xl text-indigo-700 dark:text-indigo-200 tracking-tight hidden sm:inline">Bimbo Retail</span>
            </a>
        </div>
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
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
<<<<<<< HEAD
        {{-- Removed top navigation bar to keep only the sidebar navigation --}}
        <!-- End Navigation -->
=======
        <nav class="sticky top-0 z-30 bg-white/90 backdrop-blur-lg shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <img class="h-10 w-auto rounded shadow" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                            <span class="font-bold text-xl text-indigo-700 tracking-tight hidden sm:inline">Bimbo Retail</span>
                        </a>
                        <!-- Hamburger (mobile) -->
                        <button id="nav-toggle" class="sm:hidden ml-2 p-2 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                    <!-- Navigation Links -->
                    <div id="nav-menu" class="hidden sm:flex space-x-6 items-center">
                        <a href="{{ route('retail.orders.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.orders.*') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Bread Orders
                        </a>
                        <a href="{{ route('retail.inventory.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.inventory.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                            Inventory Levels
                        </a>
                        <a href="{{ route('retail.inventory.check') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.inventory.check') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4"/></svg>
                            Check Inventory
                        </a>
                        <a href="{{ route('retail.forecast.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.forecast.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Demand Forecast
                        </a>
                        <a href="{{ route('retail.chat.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.chat.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2m4-4h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                            Chat with Suppliers
                        </a>
                        <a href="{{ route('retail.products.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('retail.products.index') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                            Product Catalog
                        </a>
                        @yield('navigation-links')
                    </div>
                    <!-- End Navigation Links -->
                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative ml-3">
                            <div>
                                <button type="button" class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="{{ auth()->user()->name }}">
                                </button>
                            </div>
                            <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Edit Profile</a>
                                <a href="{{ route('password.update') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Change Password</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
>>>>>>> 22cd29b7c6c2f6e628fe93cc67f3a8c59246ef07
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
    @push('scripts')
    <script>
        // Dark mode toggle logic
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('dark-mode-toggle');
            toggle.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
            // On load, set theme from localStorage
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        });
    </script>
    @endpush
</body>
</html>

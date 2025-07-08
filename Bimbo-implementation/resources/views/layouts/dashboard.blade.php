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
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="sticky top-0 z-30 bg-white/70 backdrop-blur-lg shadow-lg border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <img class="h-10 w-auto rounded shadow" src="{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                            <span class="font-bold text-xl text-indigo-700 tracking-tight hidden sm:inline">Bimbo Admin</span>
                        </a>
                        <!-- Hamburger (mobile) -->
                        <button id="nav-toggle" class="sm:hidden ml-2 p-2 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    </div>
                    <!-- Navigation Links -->
                    <div id="nav-menu" class="hidden sm:flex space-x-6 items-center">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                            Admin Dashboard
                        </a>
                        <a href="{{ route('admin.vendors.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.vendors.*') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M17 8a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Vendors
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Users
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.settings') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Settings
                        </a>
                        <a href="{{ route('admin.analytics.sales_predictions') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.analytics.sales_predictions') ? 'bg-indigo-100 text-indigo-700 font-semibold shadow' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/></svg>
                            Sales Predictions
                        </a>
                        @if(auth()->user() && auth()->user()->role === 'admin')
                            <a href="{{ route('customer-segments.import.form') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('customer-segments.import.form') ? 'bg-blue-100 text-blue-700 font-semibold shadow' : 'text-blue-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Import Segments
                            </a>
                            <a href="{{ route('admin.customer-segments') }}" class="flex items-center gap-1 px-3 py-2 rounded-lg transition {{ request()->routeIs('admin.customer-segments') ? 'bg-blue-100 text-blue-700 font-semibold shadow' : 'text-blue-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v16M8 4v16" /></svg>
                                View Segments
                            </a>
                        @endif
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            View Reports
                        </a>
                    </div>
                    <!-- User Menu -->
                    <div class="flex items-center ml-4">
                        <div class="relative">
                            <button type="button" class="flex items-center gap-2 rounded-full bg-white/80 px-3 py-2 shadow hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <img class="h-8 w-8 rounded-full border-2 border-indigo-300" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="{{ auth()->user()->name }}">
                                <span class="hidden sm:inline text-gray-700 font-semibold">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="hidden absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-xl bg-white/90 py-2 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50" role="menuitem" tabindex="-1">Edit Profile</a>
                                <a href="{{ route('password.update') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50" role="menuitem" tabindex="-1">Change Password</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50" role="menuitem" tabindex="-1">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
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
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Hamburger menu toggle
        document.getElementById('nav-toggle').addEventListener('click', function() {
            var menu = document.getElementById('nav-menu');
            menu.classList.toggle('hidden');
        });
        // User menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>

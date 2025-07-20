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
    <div class="min-h-screen flex" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);">
        <!-- Sidebar Navigation -->
        <aside class="fixed top-0 left-0 h-full w-60 bg-gradient-to-b from-indigo-100 via-blue-100 to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 shadow-2xl border-r border-indigo-200 dark:border-gray-700 z-30 flex flex-col">
            <div class="flex items-center gap-2 h-20 px-6 border-b border-gray-200 dark:border-gray-700 bg-white/80 dark:bg-gray-900/80 shadow-lg">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img class="h-12 w-12 rounded-full shadow-lg border-2 border-indigo-400 dark:border-indigo-700" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                    <span class="font-extrabold text-2xl text-indigo-700 dark:text-indigo-200 tracking-tight drop-shadow-lg">Bimbo Admin</span>
                </a>
            </div>
            <nav class="flex-1 flex flex-col py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/></svg>
                    Admin Dashboard
                </a>
                <a href="{{ route('admin.vendors.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('admin.vendors.*') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M17 8a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Vendors
                </a>
                <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.settings') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('admin.settings') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Settings
                </a>
                <a href="{{ route('admin.analytics.sales_predictions') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('admin.analytics.sales_predictions') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/></svg>
                    Sales Predictions
                </a>
                <a href="{{ route('reports.index') }}" class="group flex items-center gap-3 px-5 py-3 rounded-full transition-all duration-200 font-semibold text-base shadow-sm border-l-4 {{ request()->routeIs('reports.index') ? 'bg-gradient-to-r from-indigo-200 via-blue-200 to-green-100 dark:from-indigo-900 dark:via-blue-900 dark:to-green-900 border-indigo-500 text-indigo-900 dark:text-indigo-200 shadow-lg scale-105' : 'text-gray-600 dark:text-gray-300 hover:bg-gradient-to-r hover:from-indigo-100 hover:via-blue-100 hover:to-green-50 dark:hover:from-indigo-800 dark:hover:via-blue-800 dark:hover:to-green-800 border-transparent' }}">
                    <svg class="w-6 h-6 group-hover:scale-110 group-hover:text-indigo-500 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    View Reports
                </a>
                @if(auth()->user() && auth()->user()->role === 'admin')
                {{-- Remove Import Segments link from sidebar --}}
                @endif
            </nav>
            <div class="px-6 pb-2 mt-auto">
                @auth
                <div class="mb-4 flex flex-col items-center bg-white/70 dark:bg-gray-800/70 rounded-xl shadow p-4">
                    <div class="font-semibold text-indigo-800 dark:text-indigo-200 text-lg mb-1 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-400 dark:text-indigo-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ Auth::user()->name }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ Auth::user()->email }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 font-semibold shadow hover:bg-red-200 dark:hover:bg-red-700 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                            Log Out
                        </button>
                    </form>
                </div>
                @endauth
                <button id="dark-mode-toggle" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-full bg-indigo-200 dark:bg-gray-700 text-indigo-800 dark:text-indigo-100 font-bold shadow-lg hover:bg-indigo-300 dark:hover:bg-gray-600 transition-all duration-200 group relative">
                    <svg id="dark-mode-icon" class="w-6 h-6 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path id="sun-icon" class="block dark:hidden" stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m8.66-12.66l-.71.71M4.05 19.95l-.71.71M21 12h-1M4 12H3m16.66 4.66l-.71-.71M4.05 4.05l-.71-.71M12 7a5 5 0 100 10 5 5 0 000-10z" />
                        <path id="moon-icon" class="hidden dark:block" stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z" />
                    </svg>
                    <span class="block dark:hidden">Dark Mode</span>
                    <span class="hidden dark:block">Light Mode</span>
                    <span class="absolute left-full ml-2 px-2 py-1 rounded bg-gray-800 text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">Toggle theme</span>
                </button>
            </div>
        </aside>
        <div class="flex-1 ml-60 transition-colors duration-300 bg-transparent dark:bg-gradient-to-br dark:from-gray-900 dark:via-indigo-900 dark:to-blue-900">
        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-indigo-100 dark:border-gray-700 shadow-2xl sm:rounded-2xl transition-all duration-300 hover:scale-[1.01] hover:shadow-3xl">
                        <div class="p-8 text-gray-900 dark:text-gray-100 text-lg font-medium">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>
        </div>
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
</body>
</html>

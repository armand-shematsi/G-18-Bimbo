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
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar Toggle Button (mobile only, outside sidebar) -->
        <button id="sidebarToggle" class="md:hidden fixed top-4 left-4 z-50 bg-white border border-gray-300 rounded-full p-2 shadow hover:bg-gray-200 focus:outline-none">
            <svg id="sidebarToggleIcon" class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <!-- Sidebar -->
        <aside id="sidebar" class="hidden md:flex flex-col w-48 bg-amber-50 text-gray-900 border-r border-gray-200 min-h-screen sticky md:static top-0 z-40 transition-all duration-300">
            <div class="flex items-center justify-center h-24 px-6 border-b border-gray-100 bg-white shadow-sm">
                <a href="{{ route('dashboard') }}">
                    <img class="h-16 w-auto rounded bg-white shadow" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo - Bakery Dashboard">
                </a>
            </div>
            <!-- User Menu moved from header bar -->
            <!-- (Remove the following block from the sidebar) -->
            <!-- Removed custom Production Monitoring card from sidebar -->
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('bakery.production') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('bakery.production') ? 'bg-sky-100 text-sky-700 font-bold' : 'text-gray-700 hover:bg-sky-50' }}">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13v-2a4 4 0 014-4h10a4 4 0 014 4v2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 17v1a3 3 0 01-6 0v-1" />
                    </svg>
                    Production Monitoring
                </a>
                <a href="{{ route('workforce.overview', array_filter(['supply_center_id' => request('supply_center_id')])) }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('workforce.overview') ? 'bg-sky-100 text-sky-700 font-bold' : 'text-gray-700 hover:bg-sky-50' }}">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M17 8a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Workforce Distribution Management
                </a>
                <a href="{{ route('bakery.inventory.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('bakery.inventory.*') ? 'bg-sky-100 text-sky-700 font-bold' : 'text-gray-700 hover:bg-sky-50' }}">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="7" rx="2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4" />
                    </svg>
                    Inventory Management
                </a>
                <a href="{{ url('/supplier/raw-materials/catalog') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->is('supplier/raw-materials/catalog') ? 'bg-green-100 text-green-700 font-bold' : 'text-green-600 hover:bg-green-50' }}">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 3v18m6-18v18M4 21h16a1 1 0 001-1V4a1 1 0 00-1-1H4a1 1 0 00-1 1v16a1 1 0 001 1z" />
                    </svg>
                    Order Raw Materials
                </a>
                <a href="{{ route('bakery.maintenance') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('bakery.maintenance') ? 'bg-sky-100 text-sky-700 font-bold' : 'text-gray-700 hover:bg-sky-50' }}">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21l6-6m0 0l6-6m-6 6l-6-6" />
                    </svg>
                    Machine Maintenance
                </a>
                <a href="{{ route('bakery.order-processing') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('bakery.order-processing') ? 'bg-sky-100 text-sky-700 font-bold' : 'text-gray-700 hover:bg-sky-50' }}">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h6" />
                    </svg>
                    Order Processing
                </a>
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 rounded-lg bg-blue-500 text-white font-semibold shadow hover:bg-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5" />
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen relative">
            <!-- User Avatar and Bakery Manager label in top right with dropdown -->
            <div class="absolute right-8 top-6 z-50 flex items-center gap-3">
                <div class="relative group">
                    <button type="button" class="flex items-center gap-2 rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 px-2 py-1 shadow" id="user-menu-button-main" aria-expanded="false" aria-haspopup="true">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="{{ auth()->user()->name }}">
                        <span class="font-bold text-lg text-gray-800">Bakery Manager</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="hidden group-focus-within:block group-hover:block absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button-main" tabindex="-1" id="user-menu-main">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Edit Profile</a>
                        <a href="{{ route('password.update') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Change Password</a>
                    </div>
                </div>
            </div>
            <!-- Page Content -->
            <main>
                @yield('header')
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
    </div>
    <script>
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
    <script>
        // Sidebar toggle logic for mobile only
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('hidden');
        }
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        // Ensure sidebar is visible on resize for desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
            }
        });
    </script>
    <script>
        // Dropdown logic for top right user menu
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('user-menu-button-main');
            const menu = document.getElementById('user-menu-main');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                document.addEventListener('click', function(e) {
                    if (!btn.contains(e.target)) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>

</html>
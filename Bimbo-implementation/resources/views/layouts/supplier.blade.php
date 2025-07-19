<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-blue-100 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4">
            <div class="flex items-center mb-8">
                <a href="{{ route('dashboard') }}">
                    <img class="h-10 w-auto" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                </a>
            </div>
            <!-- Profile/Account Actions - Upper Left Corner -->
            <div class="flex flex-row space-x-2 mb-6">
                <a href="{{ route('profile.edit') }}" class="px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-indigo-100">Edit Profile</a>
                <a href="{{ route('password.update') }}" class="px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-indigo-100">Change Password</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1 rounded bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-red-100">Logout</button>
                </form>
            </div>
            <nav class="flex-1 space-y-2 mt-4">
                <a href="/supplier/inventory/dashboard" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->is('supplier/inventory/dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-indigo-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h4"/></svg>
                    Inventory Dashboard
                </a>
                <a href="{{ route('supplier.inventory.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->routeIs('supplier.inventory.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-green-400 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a4 4 0 004 4h10a4 4 0 004-4V7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4a4 4 0 01-4 4H8a4 4 0 01-4-4V3"/></svg>
                    Inventory
                </a>
                <a href="{{ route('supplier.stockin.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->routeIs('supplier.stockin.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Stock In
                </a>
                <a href="{{ route('supplier.stockout.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->routeIs('supplier.stockout.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-red-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    Stock Out
                </a>
                <a href="{{ route('supplier.orders.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->routeIs('supplier.orders.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-yellow-400 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Orders
                </a>
                <a href="{{ route('supplier.chat.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 shadow-sm group {{ request()->routeIs('supplier.chat.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 text-pink-400 group-hover:text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V10a2 2 0 012-2h2m4-4v4m0 0l-2-2m2 2l2-2"/></svg>
                    Chat
                </a>
            </nav>
            <div class="mt-8 border-t pt-4">
                <div class="flex items-center space-x-3">
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name) }}&background=random" alt="{{ optional(auth()->user())->name }}">
                    <div>
                        <div class="font-semibold text-gray-900">{{ optional(auth()->user())->name }}</div>
                        <div class="text-xs text-gray-500">Supplier</div>
                    </div>
                </div>
                <!-- Removed old profile links from here -->
            </div>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        @yield('header')
                    </h2>
                </div>
            </header>
            <main class="flex-1">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>

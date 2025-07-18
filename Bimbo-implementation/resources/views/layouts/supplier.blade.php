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
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col py-8 px-4">
            <div class="flex items-center mb-8">
                <a href="{{ route('dashboard') }}">
                    <img class="h-10 w-auto" src="{{ asset('images/k-photo-recipe_ramp_up-2021-11-potato-bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                </a>
            </div>
            <nav class="flex-1 space-y-2">
                <a href="/supplier/inventory/dashboard" class="block px-3 py-2 rounded {{ request()->is('supplier/inventory/dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Inventory Dashboard</a>
                <a href="{{ route('supplier.inventory.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('supplier.inventory.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Inventory</a>
                <a href="{{ route('supplier.stockin.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('supplier.stockin.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Stock In</a>
                <a href="{{ route('supplier.stockout.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('supplier.stockout.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Stock Out</a>
                <a href="{{ route('supplier.orders.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('supplier.orders.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Orders</a>
                <a href="{{ route('supplier.chat.index') }}" class="block px-3 py-2 rounded {{ request()->routeIs('supplier.chat.index') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-indigo-50' }}">Chat</a>
            </nav>
            <div class="mt-8 border-t pt-4">
                <div class="flex items-center space-x-3">
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(optional(auth()->user())->name) }}&background=random" alt="{{ optional(auth()->user())->name }}">
                    <div>
                        <div class="font-semibold text-gray-900">{{ optional(auth()->user())->name }}</div>
                        <div class="text-xs text-gray-500">Supplier</div>
                    </div>
                </div>
                <div class="mt-4 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-700 hover:underline">Edit Profile</a>
                    <a href="{{ route('password.update') }}" class="block text-sm text-gray-700 hover:underline">Change Password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left text-sm text-gray-700 hover:underline">Logout</button>
                    </form>
                </div>
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

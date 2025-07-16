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
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}">
                                <img class="h-8 w-auto" src="{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}" alt="Bimbo Logo">
                            </a>
                        </div>
                        <!-- Bakery Manager Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('bakery.production') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('bakery.production') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                Production Monitoring
                            </a>
                            <a href="{{ route('workforce.overview', array_filter(['supply_center_id' => request('supply_center_id')])) }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('workforce.overview') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                Workforce Distribution Management
                            </a>
                            <a href="{{ route('bakery.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('bakery.inventory.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                Inventory Management
                            </a>
                            <a href="{{ url('/supplier/raw-materials/catalog') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->is('supplier/raw-materials/catalog') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-green-600 hover:text-green-800 hover:border-green-600' }} text-sm font-medium leading-5 focus:outline-none focus:border-green-700 transition duration-150 ease-in-out">
                                Order Raw Materials
                            </a>
                            <a href="{{ route('bakery.maintenance') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('bakery.maintenance') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                Machine Maintenance
                            </a>
                            <a href="{{ route('bakery.order-processing') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('bakery.order-processing') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                Order Processing
                            </a>
                        </div>
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full border-2 border-yellow-400 bg-yellow-400 text-white font-bold text-lg mr-2">
                                        {{ strtoupper(collect(auth()->user()->name)->map(fn($word) => $word[0])->join('')) }}
                                    </span>
                                    <span class="flex flex-col items-start">
                                        <span class="font-semibold text-gray-900 leading-tight">{{ auth()->user()->name }}</span>
                                        <span class="text-xs text-gray-500 font-medium">Bakery Manager</span>
                                    </span>
                                </span>
                                <svg class="ml-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Edit Profile</a>
                                <a href="{{ route('password.update') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Change Password</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1">Logout</button>
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
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>

</html>
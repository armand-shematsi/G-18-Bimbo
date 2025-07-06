<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
                        {{ config('app.name', 'Bimbo Bread Management') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-primary">
                        {{ __('Dashboard') }}
                    </a>
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('customer.orders.*') ? 'border-primary text-gray-900' : 'border-transparent text-green-600 hover:text-green-700 hover:border-green-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-green-600">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Place Order
                            </a>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('customer-segments.import.form') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('customer-segments.import.form') ? 'border-primary text-gray-900' : 'border-transparent text-blue-600 hover:text-blue-700 hover:border-blue-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-blue-600">
                                <svg class="w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Import Customer Segments
                            </a>
                        @endif
                    @endauth
                    @guest
                        <a href="{{ route('vendor.register') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.register') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-primary">
                            {{ __('Register as Vendor') }}
                        </a>
                    @endguest
                    @auth
                        @if(auth()->user()->role === 'retail_manager')
                            <a href="{{ route('retail.orders.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('retail.orders.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-primary">
                                {{ __('Orders') }}
                            </a>
                            <a href="{{ route('retail.inventory.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('retail.inventory.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-primary">
                                {{ __('Inventory') }}
                            </a>
                            <a href="{{ route('retail.forecast.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('retail.forecast.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none focus:border-primary">
                                {{ __('Forecast') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right">
                            <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Profile') }}
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                {{ __('Dashboard') }}
            </a>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a href="{{ route('customer.orders.create') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('customer.orders.*') ? 'border-green-600 text-green-700 bg-green-50' : 'border-transparent text-green-600 hover:text-green-800 hover:bg-green-50 hover:border-green-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-green-800 focus:bg-green-50 focus:border-green-300">
                        <svg class="w-4 h-4 mr-1 inline text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Place Order
                    </a>
                @endif
            @endauth
            @guest
                <a href="{{ route('vendor.register') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('vendor.register') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                    {{ __('Register as Vendor') }}
                </a>
            @endguest
            @auth
                @if(auth()->user()->role === 'retail_manager')
                    <a href="{{ route('retail.orders.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('retail.orders.*') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                        {{ __('Orders') }}
                    </a>
                    <a href="{{ route('retail.inventory.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('retail.inventory.*') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                        {{ __('Inventory') }}
                    </a>
                    <a href="{{ route('retail.forecast.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('retail.forecast.*') ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium transition duration-150 ease-in-out focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                        {{ __('Forecast') }}
                    </a>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                        {{ __('Profile') }}
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>

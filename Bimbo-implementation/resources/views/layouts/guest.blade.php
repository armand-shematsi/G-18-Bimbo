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

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#4F46E5',
                        }
                    }
                }
            }
        </script>

        <style>
            .fade-in {
                animation: fadeIn 0.5s ease-in;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .hover-scale {
                transition: transform 0.2s ease-in-out;
            }
            .hover-scale:hover {
                transform: scale(1.05);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="flex items-center justify-center mb-8">
                <a href="/" class="flex items-center space-x-3 hover-scale">
                    <img src="{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}" 
                         alt="Bimbo Logo" 
                         class="w-20 h-20 object-cover rounded-full shadow-lg"
                         style="object-position: center;">
                    <span class="text-3xl font-bold bg-gradient-to-r from-gray-700 to-gray-900 bg-clip-text text-transparent">
                        Bimbo Bread Management
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-xl overflow-hidden sm:rounded-lg fade-in">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-center text-gray-800">
                        @if(request()->routeIs('vendor.register'))
                            {{ __('Vendor Registration') }}
                        @else
                            {{ __('Welcome') }}
                        @endif
                    </h2>
                    <p class="text-center text-gray-600 mt-2">
                        @if(request()->routeIs('vendor.register'))
                            {{ __('Join our network of trusted vendors') }}
                        @endif
                    </p>
                </div>

                {{ $slot }}

                <div class="mt-8 text-center border-t border-gray-100 pt-6">
                    @if(request()->routeIs('vendor.register'))
                        <p class="text-sm text-gray-600">
                            {{ __('Already have an account?') }}
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-300">
                                {{ __('Login here') }}
                            </a>
                        </p>
                    @else
                        <p class="text-sm text-gray-600">
                            {{ __('Want to become a vendor?') }}
                            <a href="{{ route('vendor.register') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors duration-300">
                                {{ __('Register here') }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved.') }}
            </div>
        </div>
    </body>
</html>

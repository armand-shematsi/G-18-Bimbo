<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Set character encoding and viewport for responsive design -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Set the page title from config -->
        <title>{{ config('app.name', 'Bimbo Bread Management') }}</title>

        <!-- Fonts: Preconnect and load Figtree font from Bunny.net -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS CDN and custom color config -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#4F46E5', // Main brand color
                            secondary: '#10B981', // Accent color
                        }
                    }
                }
            }
        </script>
        <!-- Custom CSS animations and styles -->
        <style>
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            @keyframes wave {
                0% { transform: rotate(0deg); }
                25% { transform: rotate(-5deg); }
                75% { transform: rotate(5deg); }
                100% { transform: rotate(0deg); }
            }
            .animate-float { animation: float 3s ease-in-out infinite; }
            .animate-wave { animation: wave 2s ease-in-out infinite; transform-origin: center; }
            .text-gradient {
                background: linear-gradient(45deg, #4F46E5, #10B981);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-50px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes slideInRight {
                from { opacity: 0; transform: translateX(50px); }
                to { opacity: 1; transform: translateX(0); }
            }
            .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
            .animate-slide-in-left { animation: slideInLeft 0.6s ease-out forwards; }
            .animate-slide-in-right { animation: slideInRight 0.6s ease-out forwards; }
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
            .delay-400 { animation-delay: 400ms; }
            .delay-500 { animation-delay: 500ms; }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="min-h-screen">
            <!-- Navigation Bar: Shows logo and login/register links -->
            <nav class="bg-white shadow-lg fixed w-full z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo image -->
                            <img src="{{ asset('images/k-Photo-Recipe Ramp Up-2021-11-Potato-Bread-potato_bread_01.jpeg') }}" 
                                 alt="Bimbo Logo" 
                                 class="h-10 w-10 rounded-full object-cover">
                            <span class="ml-3 text-xl font-semibold text-gray-800">Bimbo Bread Management</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            @if (Route::has('login'))
                                @auth
                                    <!-- If logged in, show dashboard link -->
                                    <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                                @else
                                    <!-- If not logged in, show login and vendor register links -->
                                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Log in</a>
                                    @if (Route::has('vendor.register'))
                                        <a href="{{ route('vendor.register') }}" class="bg-primary text-white hover:bg-primary/90 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">Register as Vendor</a>
                                    @endif
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Hero Section: Main headline and call-to-action -->
            <div class="relative bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Bimbo Supply Chain</span>
                            <span class="block text-blue-600">Fresh Bread, Everywhere</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                            Join the world's leading bread distribution network. Our platform connects bakers, suppliers, and retailers to ensure fresh bread reaches customers daily.
                        </p>
                        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                            <div class="rounded-md shadow">
                                <!-- Get Started button -->
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    Get Started
                                </a>
                            </div>
                            <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                                <!-- Learn More button -->
                                <a href="#about" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 md:py-4 md:text-lg md:px-10">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bread Stats: Quick facts about the business -->
            <div class="bg-blue-50">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-2">33+</div>
                            <div class="text-gray-600">Countries with Fresh Bread</div>
                        </div>
                        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                            <div class="text-gray-600">Daily Bread Deliveries</div>
                        </div>
                        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-2">98%</div>
                            <div class="text-gray-600">Freshness Guarantee</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section: Highlights main features -->
            <div class="py-12 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center">
                        <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Features</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Everything you need to succeed
                        </p>
                    </div>

                    <div class="mt-10">
                        <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                    <!-- Inventory icon -->
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Inventory Management</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Track your bread inventory in real-time, manage stock levels, and receive alerts for low inventory.
                                    </p>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                    <!-- Order processing icon -->
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Order Processing</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Streamline your order processing with our intuitive system. Track orders, manage deliveries, and maintain customer satisfaction.
                                    </p>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                    <!-- Analytics icon -->
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-16">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Analytics & Reporting</h3>
                                    <p class="mt-2 text-base text-gray-500">
                                        Get detailed insights into your business performance with comprehensive analytics and reporting tools.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Section -->
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Global Network</h2>
                        <p class="text-lg text-gray-600">Connecting the world's bread supply chain</p>
                    </div>

                    <!-- Network Features -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="text-blue-600 mb-4">
                                <!-- Global reach icon -->
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Global Reach</h3>
                            <p class="text-gray-600">Operating in 33+ countries with a network of trusted partners and suppliers.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="text-blue-600 mb-4">
                                <!-- Quality assurance icon -->
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Quality Assurance</h3>
                            <p class="text-gray-600">Rigorous quality control at every step of the supply chain.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="text-blue-600 mb-4">
                                <!-- Real-time tracking icon -->
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Real-time Tracking</h3>
                            <p class="text-gray-600">Advanced tracking systems for complete supply chain visibility.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="text-blue-600 mb-4">
                                <!-- Partner network icon -->
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Partner Network</h3>
                            <p class="text-gray-600">Strong relationships with suppliers, distributors, and retailers.</p>
                        </div>
                    </div>

                    <!-- Network Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl font-bold text-blue-600 mb-2">33+</div>
                            <div class="text-gray-600">Countries</div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                            <div class="text-gray-600">Distribution Centers</div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow-md text-center transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl font-bold text-blue-600 mb-2">50K+</div>
                            <div class="text-gray-600">Retail Partners</div>
                        </div>
                    </div>

                    <!-- Network Benefits -->
                    <div class="mt-12 text-center">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Why Join Our Network?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">For Suppliers</h4>
                                <p class="text-gray-600">Access to global markets and reliable distribution channels</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">For Distributors</h4>
                                <p class="text-gray-600">Efficient logistics and real-time inventory management</p>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">For Retailers</h4>
                                <p class="text-gray-600">Consistent supply of fresh products and quality assurance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                        <p class="text-lg text-gray-600">Choose the perfect plan for your business needs</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                        <!-- Basic Plan -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                            <div class="p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Basic</h3>
                                <div class="text-4xl font-bold text-blue-600 mb-6">
                                    $29<span class="text-lg text-gray-500">/month</span>
                                </div>
                                <ul class="space-y-4 mb-8">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Up to 5 vendors
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Basic analytics
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Email support
                                    </li>
                                </ul>
                                <a href="{{ route('vendor.register') }}" class="block w-full py-3 px-6 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-300">
                                    Get Started
                                </a>
                            </div>
                        </div>

                        <!-- Pro Plan -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 border-2 border-blue-600">
                            <div class="p-8">
                                <div class="absolute top-0 right-0 bg-blue-600 text-white px-4 py-1 rounded-bl-lg">
                                    Popular
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pro</h3>
                                <div class="text-4xl font-bold text-blue-600 mb-6">
                                    $79<span class="text-lg text-gray-500">/month</span>
                                </div>
                                <ul class="space-y-4 mb-8">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Up to 20 vendors
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Advanced analytics
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        24/7 priority support
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Custom integrations
                                    </li>
                                </ul>
                                <div class="mt-8">
                                <a href="{{ route('vendor.register') }}" class="block w-full py-3 px-6 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-300">
                                    Get Started
                                </a>
                                </div>
                            </div>
                        </div>

                        <!-- Enterprise Plan -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105">
                            <div class="p-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                                <div class="text-4xl font-bold text-blue-600 mb-6">
                                    Custom
                                </div>
                                <ul class="space-y-4 mb-8">
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Unlimited vendors
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Custom analytics
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Dedicated support
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Custom development
                                    </li>
                                </ul>
                                <a href="{{ route('vendor.register') }}" class="block w-full py-3 px-6 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-300">
                                    Contact Sales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                        <p class="text-lg text-gray-600">Trusted by businesses across the supply chain</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Testimonial 1 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-100">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Helen+Liz&background=0D8ABC&color=fff" alt="Helen Liz" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Helen Liz</h4>
                                    <p class="text-gray-500">Bakery Owner</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"This platform has revolutionized how we manage our bakery's supply chain. The real-time tracking and automated ordering system have saved us countless hours."</p>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-200">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Amara+Roy&background=0D8ABC&color=fff" alt="Amara Roy" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Amara Roy</h4>
                                    <p class="text-gray-500">Distributor</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The analytics and reporting features have helped us optimize our delivery routes and reduce operational costs significantly. A game-changer for our business."</p>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-300">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Armand+Shematsi&background=0D8ABC&color=fff" alt="Armand Shematsi" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Armand Shematsi</h4>
                                    <p class="text-gray-500">Retail Manager</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The inventory management system is incredibly intuitive. We've seen a 30% reduction in stockouts since implementing this solution."</p>
                        </div>

                        <!-- Testimonial 4 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-400">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Mwanabasa+Hanes&background=0D8ABC&color=fff" alt="Mwanabasa Hanes" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Mwanabasa Hanes</h4>
                                    <p class="text-gray-500">Supply Chain Director</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The platform's ability to handle multiple suppliers and complex logistics has transformed our operations. The customer support team is exceptional."</p>
                        </div>

                        <!-- Testimonial 5 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-500">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Marcus+Williams&background=0D8ABC&color=fff" alt="Marcus Williams" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Marcus Williams</h4>
                                    <p class="text-gray-500">Quality Control Manager</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The quality tracking features have helped us maintain consistent standards across all our products. The automated alerts are particularly useful."</p>
                        </div>

                        <!-- Testimonial 6 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-100">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Maria+Garcia&background=0D8ABC&color=fff" alt="Maria Garcia" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Maria Garcia</h4>
                                    <p class="text-gray-500">Procurement Specialist</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The vendor management system has streamlined our procurement process. We can now handle more orders with less effort and better accuracy."</p>
                        </div>

                        <!-- Testimonial 7 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-200">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=David+Kim&background=0D8ABC&color=fff" alt="David Kim" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">David Kim</h4>
                                    <p class="text-gray-500">Operations Manager</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The real-time tracking and automated notifications have transformed our delivery operations. We've reduced delivery times by 25% since implementation."</p>
                        </div>

                        <!-- Testimonial 8 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-300">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Lisa+Patel&background=0D8ABC&color=fff" alt="Lisa Patel" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">Lisa Patel</h4>
                                    <p class="text-gray-500">Supply Chain Analyst</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"The data analytics tools have given us unprecedented insights into our supply chain. We can now make data-driven decisions that have improved our efficiency by 40%."</p>
                        </div>

                        <!-- Testimonial 9 -->
                        <div class="bg-white p-6 rounded-lg shadow-md transform hover:scale-105 transition-transform duration-300 animate-fade-in-up delay-400">
                            <div class="flex items-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=James+Wilson&background=0D8ABC&color=fff" alt="James Wilson" class="w-12 h-12 rounded-full">
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">James Wilson</h4>
                                    <p class="text-gray-500">Regional Manager</p>
                                </div>
                            </div>
                            <p class="text-gray-600">"Managing multiple locations has never been easier. The centralized dashboard gives us complete visibility across all our operations."</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section id="about" class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">About Bimbo Supply Chain</h2>
                        <p class="text-lg text-gray-600 mb-8">Delivering fresh bread to the world since 1945</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 p-8 rounded-lg">
                            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Our Story</h3>
                            <p class="text-gray-600 mb-4">
                                Grupo Bimbo is the world's largest baking company, with a presence in 33 countries across the Americas, Europe, Asia, and Africa. Our supply chain platform represents the next evolution in our commitment to delivering fresh, quality bread products to customers worldwide.
                            </p>
                            <p class="text-gray-600">
                                With over 75 years of experience in the baking industry, we've built a reputation for excellence, innovation, and reliability. Our digital supply chain platform brings together the best of our traditional expertise with cutting-edge technology.
                            </p>
                        </div>
                        <div class="bg-gray-50 p-8 rounded-lg">
                            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Our Mission</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">Ensure fresh bread delivery to every corner of the world</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">Connect and empower our global network of partners</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">Maintain the highest standards of quality and freshness</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">Drive innovation in supply chain management</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="lg:text-center mb-12">
                        <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Contact Our Sales Team</h2>
                        <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                            Get in touch
                        </p>
                        <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                            Have questions about our supply chain solutions? Our team is here to help.
                        </p>
                    </div>

                    <div class="mt-12 max-w-lg mx-auto">
                        <form action="mailto:armandshematsi@gmail.com" method="post" enctype="text/plain" class="grid grid-cols-1 gap-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <div class="mt-1">
                                    <input type="text" name="name" id="name" required class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email" required class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <div class="mt-1">
                                    <input type="tel" name="phone" id="phone" class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <div class="mt-1">
                                    <textarea id="message" name="message" rows="4" required class="py-3 px-4 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Success Modal -->
            <div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-lg shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Message Sent!</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Thank you for contacting us. We'll get back to you soon.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>

            <script>
                function showSuccessMessage() {
                    document.getElementById('successModal').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('successModal').classList.add('hidden');
                }
            </script>

            <!-- Footer -->
            <footer class="bg-gray-800">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-white text-lg font-semibold mb-4">About Us</h3>
                            <p class="text-gray-400">Bimbo Bread Management helps vendors streamline their operations and grow their business.</p>
                        </div>
                        <div>
                            <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-gray-400 hover:text-white">Home</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Features</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Pricing</a></li>
                                <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-white text-lg font-semibold mb-4">Contact Info</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li>Email: info@bimbo.com</li>
                                <li>Phone: +1 (555) 123-4567</li>
                                <li>Address: 123 Bread Street, City</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-white text-lg font-semibold mb-4">Follow Us</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <span class="sr-only">Facebook</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <span class="sr-only">Twitter</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <span class="sr-only">LinkedIn</span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                        <p class="text-base text-gray-400">
                            &copy; {{ date('Y') }} Bimbo Bread Management. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Add Leaflet CSS and JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize the map
                const map = L.map('map').setView([20, 0], 2);

                // Add the OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Sample locations (replace with actual Bimbo locations)
                const locations = [
                    { lat: 19.4326, lng: -99.1332, title: 'Mexico City, Mexico' },
                    { lat: 40.7128, lng: -74.0060, title: 'New York, USA' },
                    { lat: 51.5074, lng: -0.1278, title: 'London, UK' },
                    { lat: 35.6762, lng: 139.6503, title: 'Tokyo, Japan' },
                    { lat: 23.1291, lng: 113.2644, title: 'Guangzhou, China' },
                    { lat: 19.0760, lng: 72.8777, title: 'Mumbai, India' },
                    { lat: 33.8688, lng: 151.2093, title: 'Sydney, Australia' },
                    { lat: 55.7558, lng: 37.6173, title: 'Moscow, Russia' },
                    { lat: 28.6139, lng: 77.2090, title: 'New Delhi, India' },
                    { lat: 31.2304, lng: 121.4737, title: 'Shanghai, China' }
                ];

                // Custom icon for markers
                const customIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                // Add markers for each location
                locations.forEach(location => {
                    const marker = L.marker([location.lat, location.lng], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`<div class="p-2"><strong>${location.title}</strong></div>`);
                });
            });
        </script>
    </body>
</html>


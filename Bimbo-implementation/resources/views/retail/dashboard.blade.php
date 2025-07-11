@extends('layouts.retail-manager')

@section('header')
    <div class="mb-10 text-center">
        <h1 class="font-extrabold text-3xl text-gray-900 leading-tight tracking-tight mb-2">Retail Manager Dashboard</h1>
        <p class="text-gray-500 text-lg">Quick summary of your most important metrics</p>
    </div>
@endsection

@section('fullpage')
<div class="flex min-h-screen bg-gradient-to-br from-blue-50 to-white items-center justify-center">
    <main class="w-full max-w-5xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-blue-700 mb-1">₦120,000</div>
                <div class="font-semibold text-gray-700">Total Sales Today</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-green-700 mb-1">24</div>
                <div class="font-semibold text-gray-700">Total Orders Today</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-purple-700 mb-1">₦1,200,000</div>
                <div class="font-semibold text-gray-700">Inventory Value</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-red-600 mb-1">7</div>
                <div class="font-semibold text-gray-700">Low Stock Items</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-yellow-600 mb-1">3</div>
                <div class="font-semibold text-gray-700">Pending Orders</div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow flex flex-col items-center text-center">
                <div class="text-2xl font-bold text-gray-700 mb-1">₦5,000</div>
                <div class="font-semibold text-gray-700">Returns/Refunds Today</div>
            </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow max-w-2xl mx-auto">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Top-Selling Products</h2>
            <ul class="divide-y divide-gray-200">
                <li class="py-2 flex justify-between"><span>White Bread</span><span class="font-semibold">120 sold</span></li>
                <li class="py-2 flex justify-between"><span>Brown Bread</span><span class="font-semibold">90 sold</span></li>
                <li class="py-2 flex justify-between"><span>Biscuits</span><span class="font-semibold">75 sold</span></li>
                <li class="py-2 flex justify-between"><span>Cookies</span><span class="font-semibold">60 sold</span></li>
            </ul>
        </div>
    </main>
</div>
@endsection

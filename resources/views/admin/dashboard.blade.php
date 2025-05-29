@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-brown">Admin Dashboard ☕</h1>
                <p class="text-gray-600 mt-1">Manage users, orders, and inventory</p>
            </div>
            <div class="text-brown font-semibold">
                Logged in as: {{ Auth::user()->name }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Stats Cards -->
            <div class="bg-white p-4 rounded-xl shadow-md text-center">
                <h3 class="text-brown text-lg font-bold">Users</h3>
                <p class="text-2xl font-bold">152</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md text-center">
                <h3 class="text-brown text-lg font-bold">Orders</h3>
                <p class="text-2xl font-bold">320</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md text-center">
                <h3 class="text-brown text-lg font-bold">Products</h3>
                <p class="text-2xl font-bold">24</p>
            </div>
            <div class="bg-white p-4 rounded-xl shadow-md text-center">
                <h3 class="text-brown text-lg font-bold">Revenue</h3>
                <p class="text-2xl font-bold">₱12,000</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Management -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-brown mb-2">Manage Users</h2>
                <p class="text-sm text-gray-600">Add, remove or update customer accounts.</p>
                <a href="#" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">Go to Users</a>
            </div>

            <!-- Inventory -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold text-brown mb-2">Inventory</h2>
                <p class="text-sm text-gray-600">View and update coffee products and stock.</p>
                <a href="#" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">Manage Inventory</a>
            </div>
        </div>
    </div>
</div>
@endsection 
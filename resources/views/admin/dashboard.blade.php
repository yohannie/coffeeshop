@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header with Stats -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-brown">Admin Dashboard ☕</h1>
                    <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-brown/10 p-4 rounded-lg">
                    <h3 class="text-brown text-sm font-semibold">Total Users</h3>
                    <p class="text-2xl font-bold text-brown">{{ $stats['users_count'] }}</p>
                </div>
                <div class="bg-brown/10 p-4 rounded-lg">
                    <h3 class="text-brown text-sm font-semibold">Total Orders</h3>
                    <p class="text-2xl font-bold text-brown">{{ $stats['orders_count'] }}</p>
                </div>
                <div class="bg-brown/10 p-4 rounded-lg">
                    <h3 class="text-brown text-sm font-semibold">Products</h3>
                    <p class="text-2xl font-bold text-brown">{{ $stats['products_count'] }}</p>
                </div>
                <div class="bg-brown/10 p-4 rounded-lg">
                    <h3 class="text-brown text-sm font-semibold">Total Revenue</h3>
                    <p class="text-2xl font-bold text-brown">₱{{ number_format($stats['revenue'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Main Menu Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- User Management Card -->
            <a href="{{ route('admin.users.index') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">User Management</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <p class="text-gray-600">Manage customer accounts and roles</p>
            </a>

            <!-- Product Management Card -->
            <a href="{{ route('admin.products.index') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">Product Management</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-gray-600">Add and manage products</p>
            </a>

            <!-- Inventory Management Card -->
            <a href="{{ route('admin.products.index') }}?view=inventory" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">Inventory</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-gray-600">Monitor stock levels and availability</p>
            </a>

            <!-- Active Orders Card -->
            <a href="{{ route('admin.orders.index') }}?status=active" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">Active Orders</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-600">View and manage ongoing orders</p>
            </a>

            <!-- Completed Orders Card -->
            <a href="{{ route('admin.orders.index') }}?status=completed" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">Completed Orders</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-600">View order history and reports</p>
            </a>

            <!-- Reports Card -->
            <a href="{{ route('admin.reports') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-brown">Reports</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-brown" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-gray-600">View sales and analytics</p>
            </a>
        </div>
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Coffee Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>
<body class="bg-gray-100">
    <!-- New Order Notification -->
    <div id="new-order-notification" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <div>
                <p class="font-bold">New Order Received!</p>
                <p class="text-sm" id="new-order-details"></p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="hidden fixed top-4 right-4 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline"></span>
    </div>

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-800">Coffee Shop Admin</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- User Management Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">User Management</h3>
                    <button onclick="showCreateModal()" 
                        class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500">
                        Add New User
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Search users..." 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                    </div>
                    <div>
                        <select id="roleFilter" class="rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('name')">
                                                Name
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('email')">
                                                Email
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('role')">
                                                Role
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="sortTable('created_at')">
                                                Joined
                                                <span class="sort-icon ml-1">↕</span>
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="usersTableBody">
                                        @foreach($users as $user)
                                        <tr id="user-row-{{ $user->id }}" data-role="{{ $user->role_id }}" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role->name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ ucfirst($user->role->name) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="editUser({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                                @if($user->id !== auth()->id())
                                                    <button onclick="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Management Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Product Management</h3>
                
                <!-- Add Product Form -->
                <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4 mb-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" name="price" id="price" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                                <option value="Hot Coffee">Hot Coffee</option>
                                <option value="Cold Coffee">Cold Coffee</option>
                                <option value="Pastries">Pastries</option>
                            </select>
                        </div>
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="url" name="image_url" id="image_url" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500">
                            Add Product
                        </button>
                    </div>
                </form>

                <!-- Products List -->
                <div class="mt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Current Products</h4>
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($products as $product)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                                src="{{ $product->image_url }}" 
                                                                alt="{{ $product->name }}">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $product->category }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">${{ number_format($product->price, 2) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $product->is_available ? 'Available' : 'Unavailable' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-2">
                                                            {{ $product->is_available ? 'Disable' : 'Enable' }}
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Management Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Active Orders</h3>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Delivery</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($activeOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $order->items->count() }} items
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @foreach($order->items as $item)
                                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last),@endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₱{{ number_format($order->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'processing') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'ready') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->expected_delivery ? $order->expected_delivery->format('M d, Y H:i') : 'Not set' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="viewOrderDetails({{ $order->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">View</button>
                                                <select onchange="updateOrderStatus({{ $order->id }}, this.value)" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                                    <option value="" disabled>Update Status</option>
                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                    <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Complete</option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Orders Section -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Completed Orders</h3>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed At</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($completedOrders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $order->items->count() }} items
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @foreach($order->items as $item)
                                                        {{ $item->quantity }}x {{ $item->product->name }}@if(!$loop->last),@endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₱{{ number_format($order->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->updated_at->format('M d, Y H:i') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details Modal -->
        <div id="orderDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Order Details</h3>
                    <div class="mt-2 px-7 py-3" id="orderDetailsContent">
                        <!-- Content will be dynamically populated -->
                    </div>
                    <div class="items-center px-4 py-3">
                        <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create User Modal -->
        <div id="createUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Create New User</h3>
                    <form id="createUserForm" class="mt-4 space-y-4">
                        <div>
                            <label for="createName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="createName" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="createEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="createEmail" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="createRole" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="createRole" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="createPassword" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="createPassword" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="createPasswordConfirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="createPasswordConfirmation" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCreateModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500">
                                Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div id="editUserModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit User</h3>
                    <form id="editUserForm" class="mt-4 space-y-4">
                        <input type="hidden" id="editUserId">
                        <div>
                            <label for="editName" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="editName" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="editEmail" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="editEmail" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="editRole" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="editRole" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="editPassword" class="block text-sm font-medium text-gray-700">New Password (optional)</label>
                            <input type="password" name="password" id="editPassword" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div>
                            <label for="editPasswordConfirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="editPasswordConfirmation" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown-500 focus:ring-brown-500">
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeEditModal()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-brown-600 text-white rounded-md hover:bg-brown-700 focus:outline-none focus:ring-2 focus:ring-brown-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="bg-white p-4 rounded-xl shadow-md text-center">
            <h3 class="text-brown text-lg font-bold">Users</h3>
            <p class="text-2xl font-bold">{{ $stats['users_count'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-md text-center">
            <h3 class="text-brown text-lg font-bold">Orders</h3>
            <p class="text-2xl font-bold">{{ $stats['orders_count'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-md text-center">
            <h3 class="text-brown text-lg font-bold">Products</h3>
            <p class="text-2xl font-bold">{{ $stats['products_count'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-md text-center">
            <h3 class="text-brown text-lg font-bold">Revenue</h3>
            <p class="text-2xl font-bold">₱{{ number_format($stats['revenue'], 2) }}</p>
        </div>

        <!-- Inventory -->
        <div class="bg-white p-4 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold text-brown mb-2">Inventory</h2>
            <p class="text-sm text-gray-600">View and update coffee products and stock.</p>
            <a href="{{ route('admin.products.index') }}" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">Manage Inventory</a>
        </div>
    </main>

    <script>
        // Add custom Tailwind configuration
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brown: {
                            50: '#fdf8f6',
                            100: '#f2e8e5',
                            200: '#eaddd7',
                            300: '#e0cec7',
                            400: '#d2bab0',
                            500: '#bfa094',
                            600: '#a18072',
                            700: '#977669',
                            800: '#846358',
                            900: '#43302b',
                        },
                    }
                }
            }
        }

        // Setup AJAX CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Setup Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        const channel = pusher.subscribe('orders');
        
        channel.bind('new-order', function(data) {
            // Show notification
            const order = data.order;
            const notification = $('#new-order-notification');
            $('#new-order-details').html(`Order #${order.id} from ${order.user.name}`);
            notification.removeClass('hidden');
            
            // Play notification sound
            const audio = new Audio('/notification.mp3');
            audio.play();
            
            // Add the new order to the table
            const newRow = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#${order.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${order.user.name}</div>
                        <div class="text-sm text-gray-500">${order.user.email}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            ${order.items.map(item => `<div>${item.name} (x${item.quantity})</div>`).join('')}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${order.total_amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select onchange="updateOrderStatus(${order.id}, this.value)" class="text-sm rounded-full px-2 py-1 border-gray-300 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:border-brown-500 bg-yellow-100 text-yellow-800">
                            <option value="processing" selected>Processing</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready">Ready</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="datetime-local" 
                            value="${order.expected_delivery}"
                            onchange="updateDeliveryTime(${order.id}, this.value)"
                            class="text-sm rounded-md border-gray-300 focus:outline-none focus:ring-2 focus:ring-brown-500 focus:border-brown-500">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button onclick="viewOrderDetails(${order.id})" class="text-blue-600 hover:text-blue-900">
                            View Details
                        </button>
                    </td>
                </tr>
            `;
            
            // Add the new row at the top of the active orders table
            const activeOrdersTable = $('table:first tbody');
            activeOrdersTable.prepend(newRow);
            
            // Hide notification after 5 seconds
            setTimeout(() => {
                notification.addClass('hidden');
            }, 5000);
        });

        function showMessage(message, isError = false) {
            const messageDiv = $('#success-message');
            messageDiv.removeClass('hidden bg-green-100 border-green-400 text-green-700 bg-red-100 border-red-400 text-red-700');
            messageDiv.addClass(isError ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700');
            messageDiv.find('span').text(message);
            messageDiv.removeClass('hidden');
            
            setTimeout(() => {
                messageDiv.addClass('hidden');
            }, 3000);
        }

        function updateOrderStatus(orderId, status) {
            $.post(`/admin/orders/${orderId}/status`, {
                status: status,
            })
            .done(function(response) {
                showMessage('Order status updated successfully!');
                if (status === 'completed' || status === 'cancelled') {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            })
            .fail(function(xhr) {
                showMessage(xhr.responseJSON?.error || 'Error updating order status', true);
            });
        }

        function updateDeliveryTime(orderId, datetime) {
            $.post(`/admin/orders/${orderId}/status`, {
                status: $(`select[onchange="updateOrderStatus(${orderId}, this.value)"]`).val(),
                expected_delivery: datetime
            })
            .done(function(response) {
                showMessage('Delivery time updated successfully!');
            })
            .fail(function(xhr) {
                showMessage(xhr.responseJSON?.error || 'Error updating delivery time', true);
            });
        }

        function viewOrderDetails(orderId) {
            $.get(`/admin/orders/${orderId}`)
                .done(function(response) {
                    const order = response.order;
                    let html = `
                        <div class="space-y-4">
                            <div>
                                <h4 class="font-medium">Customer Information</h4>
                                <p class="text-sm text-gray-600">${order.user.name}</p>
                                <p class="text-sm text-gray-600">${order.user.email}</p>
                            </div>
                            <div>
                                <h4 class="font-medium">Order Items</h4>
                                <ul class="list-disc list-inside text-sm text-gray-600">
                    `;
                    
                    order.items.forEach(item => {
                        html += `<li>${item.product.name} - ${item.quantity}x @ $${item.price}</li>`;
                    });
                    
                    html += `
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium">Total Amount</h4>
                                <p class="text-sm text-gray-600">$${order.total_amount}</p>
                            </div>
                        </div>
                    `;
                    
                    $('#orderDetailsContent').html(html);
                    $('#orderDetailsModal').removeClass('hidden');
                })
                .fail(function(xhr) {
                    showMessage('Error loading order details', true);
                });
        }

        // Close modal when clicking the close button or outside the modal
        $('#closeModal').click(function() {
            $('#orderDetailsModal').addClass('hidden');
        });

        $(window).click(function(event) {
            const modal = $('#orderDetailsModal');
            if (event.target === modal[0]) {
                modal.addClass('hidden');
            }
        });

        // User Management Functions
        function showCreateModal() {
            $('#createUserModal').removeClass('hidden');
            $('#createUserForm')[0].reset();
        }

        function closeCreateModal() {
            $('#createUserModal').addClass('hidden');
            $('#createUserForm')[0].reset();
        }

        function editUser(userId) {
            const user = @json($users->keyBy('id'));
            const userData = user[userId];
            
            $('#editUserId').val(userId);
            $('#editName').val(userData.name);
            $('#editEmail').val(userData.email);
            $('#editRole').val(userData.role_id);
            $('#editPassword').val('');
            $('#editPasswordConfirmation').val('');
            
            $('#editUserModal').removeClass('hidden');
        }

        function closeEditModal() {
            $('#editUserModal').addClass('hidden');
            $('#editUserForm')[0].reset();
        }

        $('#createUserForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '/admin/users',
                method: 'POST',
                data: {
                    name: $('#createName').val(),
                    email: $('#createEmail').val(),
                    role_id: $('#createRole').val(),
                    password: $('#createPassword').val(),
                    password_confirmation: $('#createPasswordConfirmation').val()
                },
                success: function(response) {
                    showMessage(response.message);
                    closeCreateModal();
                    window.location.reload(); // Reload to show new user
                },
                error: function(xhr) {
                    showMessage(xhr.responseJSON?.error || 'Error creating user', true);
                }
            });
        });

        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            const userId = $('#editUserId').val();
            
            $.ajax({
                url: `/admin/users/${userId}`,
                method: 'PUT',
                data: {
                    name: $('#editName').val(),
                    email: $('#editEmail').val(),
                    role_id: $('#editRole').val(),
                    password: $('#editPassword').val(),
                    password_confirmation: $('#editPasswordConfirmation').val()
                },
                success: function(response) {
                    showMessage(response.message);
                    closeEditModal();
                    
                    // Update the user row in the table
                    const user = response.user;
                    const roleClass = user.role.name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                    
                    $(`#user-row-${userId}`).html(`
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${user.name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${user.email}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold ${roleClass}">
                                ${user.role.name.charAt(0).toUpperCase() + user.role.name.slice(1)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editUser(${user.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                            ${user.id !== {{ auth()->id() }} ? `<button onclick="deleteUser(${user.id})" class="text-red-600 hover:text-red-900">Delete</button>` : ''}
                        </td>
                    `);
                },
                error: function(xhr) {
                    showMessage(xhr.responseJSON?.error || 'Error updating user', true);
                }
            });
        });

        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }
            
            $.ajax({
                url: `/admin/users/${userId}`,
                method: 'DELETE',
                success: function(response) {
                    showMessage(response.message);
                    $(`#user-row-${userId}`).remove();
                },
                error: function(xhr) {
                    showMessage(xhr.responseJSON?.error || 'Error deleting user', true);
                }
            });
        }

        // Search and filter functionality
        $('#searchInput').on('keyup', function() {
            filterTable();
        });

        $('#roleFilter').on('change', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = $('#searchInput').val().toLowerCase();
            const roleFilter = $('#roleFilter').val();
            
            $('#usersTableBody tr').each(function() {
                const $row = $(this);
                const name = $row.data('name');
                const email = $row.data('email');
                const role = $row.data('role');
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesRole = !roleFilter || role == roleFilter;
                
                $row.toggle(matchesSearch && matchesRole);
            });
        }

        // Sorting functionality
        let currentSort = { column: '', direction: 'asc' };

        function sortTable(column) {
            const $tbody = $('#usersTableBody');
            const $rows = $tbody.find('tr').toArray();
            
            // Update sort direction
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = { column: column, direction: 'asc' };
            }
            
            // Update sort icons
            $('.sort-icon').text('↕');
            const $icon = $(`th[onclick="sortTable('${column}')"] .sort-icon`);
            $icon.text(currentSort.direction === 'asc' ? '↓' : '↑');
            
            // Sort rows
            $rows.sort((a, b) => {
                let aValue = '';
                let bValue = '';
                
                switch(column) {
                    case 'name':
                    case 'email':
                        aValue = $(a).data(column);
                        bValue = $(b).data(column);
                        break;
                    case 'role':
                        aValue = $(a).find('td:eq(2)').text().trim();
                        bValue = $(b).find('td:eq(2)').text().trim();
                        break;
                    case 'created_at':
                        aValue = new Date($(a).find('td:eq(3)').text()).getTime();
                        bValue = new Date($(b).find('td:eq(3)').text()).getTime();
                        break;
                }
                
                if (currentSort.direction === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            // Reorder table
            $tbody.empty();
            $rows.forEach(row => $tbody.append(row));
        }
    </script>
</body>
</html> 
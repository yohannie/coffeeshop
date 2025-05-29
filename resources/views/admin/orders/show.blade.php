@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-brown">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600 mt-1">Order Details</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url()->previous() }}" class="text-brown hover:text-brown-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Order Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-brown/10 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-brown mb-4">Customer Information</h3>
                    <div class="space-y-2">
                        <p class="text-sm">
                            <span class="font-medium">Name:</span> 
                            {{ $order->user->name }}
                        </p>
                        <p class="text-sm">
                            <span class="font-medium">Email:</span> 
                            {{ $order->user->email }}
                        </p>
                        <p class="text-sm">
                            <span class="font-medium">Order Date:</span> 
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="bg-brown/10 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-brown mb-4">Order Status</h3>
                    <div class="space-y-2">
                        <p class="text-sm">
                            <span class="font-medium">Current Status:</span>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="mt-4">
                            @csrf
                            @method('POST')
                            <select name="status" class="text-sm rounded-md border-gray-300 focus:border-brown focus:ring focus:ring-brown focus:ring-opacity-50 w-full">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="mt-2 w-full bg-brown text-white px-4 py-2 rounded-md hover:bg-brown-dark transition-colors">
                                Update Status
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-brown mb-4">Order Items</h3>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->product->category }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱{{ number_format($item->price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total:</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
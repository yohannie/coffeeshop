@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-brown mb-6">My Orders</h1>

            @if($orders->isEmpty())
                <p class="text-gray-600">You haven't placed any orders yet.</p>
            @else
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-brown">Order #{{ $order->id }}</h2>
                                    <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-brown">₱{{ number_format($order->total_amount, 2) }}</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Order Items:</h3>
                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <div>
                                                <p class="font-medium">{{ $item->product->name }}</p>
                                                <p class="text-gray-500">Quantity: {{ $item->quantity }}</p>
                                            </div>
                                            <p class="text-gray-900">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($order->expected_delivery)
                                <div class="mt-4 text-sm text-gray-600">
                                    Expected Delivery: {{ $order->expected_delivery->format('M d, Y h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 
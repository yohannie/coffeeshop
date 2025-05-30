@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center mb-8">
                <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h1 class="mt-4 text-2xl font-bold text-brown">Order Placed Successfully!</h1>
                <p class="mt-2 text-gray-600">Your order number is #{{ $order->id }}</p>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-brown mb-4">Order Details</h2>
                <div class="space-y-4">
                    @if($order->user)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer Name:</span>
                            <span class="font-medium">{{ $order->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $order->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium">{{ $order->user->phone }}</span>
                        </div>
                        @if($order->user->address)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Address:</span>
                                <span class="font-medium">{{ $order->user->address }}</span>
                            </div>
                        @endif
                    @else
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer Name:</span>
                            <span class="font-medium">{{ $order->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ $order->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium">{{ $order->phone }}</span>
                        </div>
                        @if($order->delivery_address)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery Address:</span>
                                <span class="font-medium">{{ $order->delivery_address }}</span>
                            </div>
                        @endif
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium capitalize">{{ $order->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Expected Delivery:</span>
                        <span class="font-medium">{{ $order->expected_delivery->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-brown bg-brown/10 hover:bg-brown/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown">
                    Return to Home
                </a>
                @if($order->user)
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brown hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown">
                        Go to User Dashboard
                    </a>
                @else
                    <form action="{{ route('login') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $order->email }}">
                        <input type="hidden" name="password" value="{{ $order->password }}">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brown hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown">
                            Go to User Dashboard
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-brown mb-6">Register & Place Order</h1>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('checkout.guest.process') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Registration Information -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-brown">Registration Information</h2>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown focus:ring-brown" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown focus:ring-brown" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown focus:ring-brown" required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brown focus:ring-brown" required>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-brown mb-4">Order Summary</h2>
                    
                    <div class="space-y-4">
                        @foreach($cart as $id => $item)
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item['name'] }}</h3>
                                    <p class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Total</h3>
                            <p class="text-lg font-bold text-brown">₱{{ number_format($total, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-brown text-white px-6 py-2 rounded-md hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown focus:ring-offset-2">
                        Register & Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
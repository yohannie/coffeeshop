@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h1 class="text-2xl font-bold text-brown">Welcome, {{ Auth::user()->name }}! ☕</h1>
            <p class="text-gray-600 mt-1">Here's your personalized coffee dashboard.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card: Profile -->
            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-brown mb-2">Your Profile</h2>
                <p class="text-sm text-gray-600">Manage your name, email and preferences.</p>
                <a href="#" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">Edit Profile</a>
            </div>

            <!-- Card: Orders -->
            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-brown mb-2">Your Orders</h2>
                <p class="text-sm text-gray-600">Track and view your past coffee orders.</p>
                <a href="#" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">View Orders</a>
            </div>

            <!-- Card: Loyalty Points -->
            <div class="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition">
                <h2 class="text-xl font-semibold text-brown mb-2">Loyalty Points</h2>
                <p class="text-sm text-gray-600">You have <span class="font-bold">120</span> beans!</p>
                <a href="#" class="inline-block mt-4 text-sm text-brown font-semibold hover:underline">Redeem Now</a>
            </div>
        </div>
    </div>
</div>
@endsection 
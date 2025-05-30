@extends('layouts.app')

@section('content')
<div class="bg-coffee min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-brown">Our Menu</h1>
            <p class="mt-2 text-gray-600">Discover our delicious coffee and pastries</p>
            @guest
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-brown text-white rounded-md hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login to Order
                    </a>
                </div>
            @endguest
        </div>

        <!-- Categories -->
        <div class="flex justify-center space-x-4 mb-8">
            <button onclick="filterProducts('all')" class="category-btn active px-4 py-2 bg-brown text-white rounded-md hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown">
                All
            </button>
            <button onclick="filterProducts('Hot Coffee')" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brown">
                Hot Coffee
            </button>
            <button onclick="filterProducts('Cold Coffee')" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brown">
                Cold Coffee
            </button>
            <button onclick="filterProducts('Pastries')" class="category-btn px-4 py-2 bg-white text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brown">
                Pastries
            </button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
            <div class="product-item bg-white overflow-hidden shadow rounded-lg" data-category="{{ $product->category }}">
                <div class="p-5">
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        @if($product->image_full_url)
                            <img src="{{ $product->image_full_url }}" alt="{{ $product->name }}" class="object-cover rounded-lg w-full h-48">
                        @else
                            <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-4xl">☕</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $product->name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ $product->description }}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                        <button onclick="addToCart({{ $product->id }})" class="px-4 py-2 bg-brown text-white rounded-md hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary and Checkout Button -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Cart Summary</h3>
                    <p class="text-sm text-gray-500">Items in cart: <span id="cart-count">0</span></p>
                    <p class="text-sm text-gray-500">Total: <span id="cart-total">₱0.00</span></p>
                </div>
                <a href="{{ route('checkout.guest') }}" class="px-6 py-3 bg-brown text-white rounded-md hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let messageTimeout;

    function filterProducts(category) {
        // Update active button
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-brown', 'text-white');
            btn.classList.add('bg-white', 'text-gray-700');
        });
        event.currentTarget.classList.remove('bg-white', 'text-gray-700');
        event.currentTarget.classList.add('active', 'bg-brown', 'text-white');

        // Show/hide products
        document.querySelectorAll('.product-item').forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function addToCart(productId) {
        const button = $(`button[onclick="addToCart(${productId})"]`);
        button.prop('disabled', true);
        button.html('<span class="inline-block animate-spin">↻</span>');

        $.post(`/cart/add/${productId}`)
            .done(function(response) {
                updateCartSummary(response.cartItems);
                showMessage(response.message);
            })
            .fail(function(xhr) {
                const error = xhr.responseJSON?.error || 'Error adding to cart. Please try again.';
                showMessage(error, true);
            })
            .always(function() {
                button.prop('disabled', false);
                button.html('Add to Cart');
            });
    }

    function updateCartSummary(cartItems) {
        const cartCount = Object.keys(cartItems).length;
        $('#cart-count').text(cartCount);
        
        let total = 0;
        Object.values(cartItems).forEach(item => {
            total += item.price * item.quantity;
        });
        $('#cart-total').text(`₱${total.toFixed(2)}`);
    }

    function showMessage(message, isError = false) {
        // Clear any existing message timeout
        if (messageTimeout) {
            clearTimeout(messageTimeout);
            $('.alert-message').remove();
        }

        const messageDiv = $('<div>')
            .addClass('alert-message')
            .addClass(isError ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700')
            .addClass('border px-4 py-3 rounded relative mb-4')
            .attr('role', 'alert')
            .html(`<span class="block sm:inline">${message}</span>`);

        $('.max-w-7xl').prepend(messageDiv);

        // Set timeout to remove message after 3 seconds
        messageTimeout = setTimeout(() => {
            messageDiv.fadeOut(() => messageDiv.remove());
        }, 3000);
    }

    // Initialize cart summary on page load
    $(document).ready(function() {
        const cart = @json(session('cart', []));
        updateCartSummary(cart);
    });
</script>
@endpush
@endsection 
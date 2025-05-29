<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Coffee Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .bg-coffee {
            background-color: #fdf6f0;
        }
        .bg-brown {
            background-color: #6F4E37;
        }
        .hover\:bg-brown-dark:hover {
            background-color: #5a3d2b;
        }
        .text-brown {
            color: #6F4E37;
        }
        .border-brown {
            border-color: #6F4E37;
        }
        .category-btn.active {
            background-color: #6F4E37;
            color: white;
        }
    </style>
</head>
<body class="bg-coffee" x-data="{ cartOpen: false }">
    <nav class="bg-brown text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold">☕ Coffee Shop</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <button @click="cartOpen = !cartOpen" class="flex items-center space-x-1 text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium cart-count">Cart (0)</span>
                        </button>
                        
                        <!-- Cart dropdown -->
                        <div x-show="cartOpen" @click.away="cartOpen = false" class="origin-top-right absolute right-0 mt-2 w-96 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <div class="px-4 py-2 border-b">
                                    <h3 class="text-lg font-medium text-gray-900">Shopping Cart</h3>
                                </div>
                                <div id="cart-items" class="px-4 py-2">
                                    <p class="text-gray-500">Your cart is empty</p>
                                </div>
                                <div class="px-4 py-2 border-t">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Total:</span>
                                        <span class="font-bold" id="cart-total">₱0.00</span>
                                    </div>
                                    <button onclick="checkout()" class="mt-4 w-full bg-brown text-white rounded-md px-4 py-2 hover:bg-brown-dark focus:outline-none focus:ring-2 focus:ring-brown">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ml-4 flex items-center space-x-4">
                        <span class="text-white">Welcome, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-white hover:text-gray-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div id="success-message" class="hidden mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"></span>
        </div>

        <!-- Categories -->
        <div class="px-4 sm:px-0 mb-8">
            <div class="flex space-x-4">
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
        </div>

        <!-- Products Grid -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
        </div>

        <!-- Order History -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Order History</h3>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-brown text-white">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Order ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Items</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Total</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $order->id }}</td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">
                                                    @foreach($order->products as $product)
                                                        <div>{{ $product->name }} (x{{ $product->pivot->quantity }})</div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('M d, Y H:i') }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No orders found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        function showMessage(message, isError = false) {
            const messageDiv = $('#success-message');
            messageDiv.removeClass('hidden bg-green-100 border-green-400 text-green-700 bg-red-100 border-red-400 text-red-700');
            messageDiv.addClass(isError ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700');
            messageDiv.find('span').text(message);
            messageDiv.removeClass('hidden');
            
            setTimeout(() => {
                messageDiv.addClass('hidden');
            }, 5000);
        }

        function addToCart(productId) {
            const button = $(`button[onclick="addToCart(${productId})"]`);
            button.prop('disabled', true);
            button.html('<span class="inline-block animate-spin">↻</span>');

            $.post(`/cart/add/${productId}`)
                .done(function(response) {
                    updateCartUI(response.cartItems);
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

        function removeFromCart(productId) {
            const button = $(`button[onclick="removeFromCart(${productId})"]`);
            button.prop('disabled', true);

            $.post(`/cart/remove/${productId}`)
                .done(function(response) {
                    updateCartUI(response.cartItems);
                    showMessage(response.message);
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON?.error || 'Error removing from cart. Please try again.';
                    showMessage(error, true);
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        }

        function updateCart(productId, quantity) {
            if (quantity < 1) return;
            
            const button = $(`button[onclick="updateCart(${productId}, ${quantity})"]`);
            button.prop('disabled', true);

            $.post(`/cart/update/${productId}`, { quantity: quantity })
                .done(function(response) {
                    updateCartUI(response.cartItems);
                    showMessage(response.message);
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON?.error || 'Error updating cart. Please try again.';
                    showMessage(error, true);
                })
                .always(function() {
                    button.prop('disabled', false);
                });
        }

        function checkout() {
            const button = $('button[onclick="checkout()"]');
            button.prop('disabled', true);
            button.html('<span class="inline-block animate-spin">↻</span> Processing...');

            $.post('/cart/checkout')
                .done(function(response) {
                    updateCartUI({});
                    showMessage(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                })
                .fail(function(xhr) {
                    const error = xhr.responseJSON?.error || 'Error placing order. Please try again.';
                    showMessage(error, true);
                    button.prop('disabled', false);
                    button.html('Place Order');
                });
        }

        function updateCartUI(cartItems) {
            const cartCount = Object.keys(cartItems).length;
            $('.cart-count').text(`Cart (${cartCount})`);
            
            if (cartCount === 0) {
                $('#cart-items').html('<p class="text-gray-500">Your cart is empty</p>');
                $('#cart-total').text('₱0.00');
                return;
            }

            let total = 0;
            let html = '<div class="space-y-4">';
            
            Object.entries(cartItems).forEach(([id, item]) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                html += `
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                            <div>
                                <h4 class="font-medium">${item.name}</h4>
                                <p class="text-sm text-gray-500">₱${item.price} x ${item.quantity}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateCart(${id}, ${item.quantity - 1})" class="text-gray-500 hover:text-gray-700 disabled:opacity-50">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="updateCart(${id}, ${item.quantity + 1})" class="text-gray-500 hover:text-gray-700 disabled:opacity-50">+</button>
                            <button onclick="removeFromCart(${id})" class="text-red-500 hover:text-red-700 disabled:opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            $('#cart-items').html(html);
            $('#cart-total').text(`₱${total.toFixed(2)}`);
        }

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
    </script>
</body>
</html> 
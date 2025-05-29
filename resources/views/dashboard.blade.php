@extends('layouts.app')

@section('content')
<!-- Order Status Notification -->
<div id="order-notification" class="hidden fixed top-4 right-4 z-50 w-full max-w-sm mx-auto sm:mx-0">
    <div class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p id="notification-message" class="text-sm font-medium text-gray-900 break-words"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="closeNotification()" class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brown-500">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Active Orders Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold mb-4">Your Active Orders</h2>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Delivery</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="activeOrdersTable">
                                @foreach($activeOrders as $order)
                                <tr id="order-{{ $order->id }}">
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6">
                                        <div class="text-sm text-gray-900">
                                            @foreach($order->items as $item)
                                                <div class="mb-1 last:mb-0">{{ $item->product->name }} (x{{ $item->quantity }})</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($order->status === 'preparing' ? 'bg-blue-100 text-blue-800' : 
                                               ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                                               'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-500">
                                        <span class="hidden sm:inline">{{ $order->expected_delivery ? $order->expected_delivery->format('M d, Y H:i') : 'Not set' }}</span>
                                        <span class="sm:hidden">{{ $order->expected_delivery ? $order->expected_delivery->format('M/d H:i') : 'Not set' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order History Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold mb-4">Order History</h2>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-3 py-3 sm:px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($completedOrders as $order)
                                <tr>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6">
                                        <div class="text-sm text-gray-900">
                                            @foreach($order->items as $item)
                                                <div class="mb-1 last:mb-0">{{ $item->product->name }} (x{{ $item->quantity }})</div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 sm:px-6 whitespace-nowrap text-sm text-gray-500">
                                        <span class="hidden sm:inline">{{ $order->updated_at->format('M d, Y H:i') }}</span>
                                        <span class="sm:hidden">{{ $order->updated_at->format('M/d H:i') }}</span>
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

@push('scripts')
<script>
    // Initialize Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    // Subscribe to the user's private channel
    const channel = pusher.subscribe('private-user.{{ auth()->id() }}');

    // Listen for order status updates
    channel.bind('order.updated', function(data) {
        const order = data.order;
        
        // Update the order status in the table
        const orderRow = $(`#order-${order.id}`);
        if (orderRow.length) {
            // Update status
            const statusClass = order.status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 
                              order.status === 'preparing' ? 'bg-blue-100 text-blue-800' : 
                              order.status === 'ready' ? 'bg-green-100 text-green-800' : 
                              'bg-gray-100 text-gray-800';
            
            orderRow.find('td:eq(3) span').attr('class', `px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}`);
            orderRow.find('td:eq(3) span').text(order.status.charAt(0).toUpperCase() + order.status.slice(1));
            
            // Update expected delivery time if provided
            if (order.expected_delivery) {
                const date = new Date(order.expected_delivery);
                const fullFormat = date.toLocaleString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit'
                });
                const shortFormat = date.toLocaleString('en-US', {
                    month: 'numeric',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit'
                });
                
                orderRow.find('td:eq(4) .sm\\:inline').text(fullFormat);
                orderRow.find('td:eq(4) .sm\\:hidden').text(shortFormat);
            }

            // Show notification
            showNotification(`Your order #${order.id} has been ${order.status}!`);

            // If order is completed or cancelled, move it to order history after a delay
            if (order.status === 'completed' || order.status === 'cancelled') {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        }
    });

    function showNotification(message) {
        $('#notification-message').text(message);
        $('#order-notification').removeClass('hidden');
        
        // Play notification sound
        const audio = new Audio('/notification.mp3');
        audio.play();

        // Auto hide after 5 seconds
        setTimeout(() => {
            closeNotification();
        }, 5000);
    }

    function closeNotification() {
        $('#order-notification').addClass('hidden');
    }
</script>
@endpush
@endsection 
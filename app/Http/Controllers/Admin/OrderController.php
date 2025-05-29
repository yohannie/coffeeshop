<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'active');
        
        $orders = Order::with(['user', 'items.product'])
            ->when($status === 'active', function($query) {
                return $query->whereNotIn('status', ['completed', 'cancelled']);
            })
            ->when($status === 'completed', function($query) {
                return $query->where('status', 'completed');
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.orders.index', [
            'orders' => $orders,
            'status' => $status
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,preparing,ready,completed,cancelled',
            'expected_delivery' => 'nullable|date',
        ]);

        $order->status = $request->status;
        if ($request->has('expected_delivery')) {
            $order->expected_delivery = $request->expected_delivery;
        }
        $order->save();

        // Broadcast to the specific user's channel
        event(new OrderStatusUpdated($order));

        return back()->with('success', 'Order status updated successfully');
    }
} 
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

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return response()->json(['order' => $order]);
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

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load('user', 'items.product')
        ]);
    }
} 
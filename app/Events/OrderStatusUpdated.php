<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('user', 'items.product');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->order->user_id);
    }

    public function broadcastAs()
    {
        return 'order.updated';
    }

    public function broadcastWith()
    {
        return [
            'order' => [
                'id' => $this->order->id,
                'status' => $this->order->status,
                'expected_delivery' => $this->order->expected_delivery,
                'total_amount' => $this->order->total_amount,
                'items' => $this->order->items->map(function ($item) {
                    return [
                        'product' => [
                            'name' => $item->product->name
                        ],
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                })
            ]
        ];
    }
} 
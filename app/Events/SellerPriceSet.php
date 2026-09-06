<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellerPriceSet implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn()
    {
        return [new PrivateChannel('user.' . $this->order->buyer_id)];
    }

    public function broadcastAs()
    {
        return 'order.price-set';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'final_price' => (float) $this->order->final_price,
            'seller_note' => $this->order->seller_price_note,
        ];
    }
}

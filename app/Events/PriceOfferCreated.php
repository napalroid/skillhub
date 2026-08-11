<?php

namespace App\Events;

use App\Models\PriceOffer;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PriceOfferCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public PriceOffer $priceOffer)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversation.' . $this->priceOffer->conversation_id)];
    }

    public function broadcastAs(): string
    {
        return 'price-offer.created';
    }

    public function broadcastWith(): array
    {
        return [
            'offer' => [
                'id' => $this->priceOffer->id,
                'original_price' => $this->priceOffer->original_price,
                'offer_price' => $this->priceOffer->offer_price,
                'note' => $this->priceOffer->note,
                'status' => $this->priceOffer->status->value,
            ],
        ];
    }
}

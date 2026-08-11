<?php

namespace App\Models;

use App\Enums\PriceOfferStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PriceOffer extends Model
{
    protected $fillable = [
        'conversation_id',
        'service_id',
        'seller_id',
        'buyer_id',
        'original_price',
        'offer_price',
        'note',
        'status',
        'expires_at',
        'accepted_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'offer_price' => 'decimal:2',
            'status' => PriceOfferStatus::class,
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', PriceOfferStatus::Pending->value);
    }

    public function isPending(): bool
    {
        return $this->status === PriceOfferStatus::Pending;
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? false;
    }
}

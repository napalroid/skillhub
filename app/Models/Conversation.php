<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = ['service_id', 'buyer_id', 'seller_id'];

    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function buyer(): BelongsTo { return $this->belongsTo(User::class, 'buyer_id'); }
    public function seller(): BelongsTo { return $this->belongsTo(User::class, 'seller_id'); }
    public function messages(): HasMany { return $this->hasMany(Message::class); }
    public function priceOffers(): HasMany { return $this->hasMany(PriceOffer::class); }
    public function latestMessage(): HasOne { return $this->hasOne(Message::class)->latestOfMany(); }

    public function hasParticipant(User $user): bool
    {
        return $this->buyer_id === $user->id || $this->seller_id === $user->id;
    }
}

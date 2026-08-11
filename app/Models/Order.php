<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['service_id', 'buyer_id', 'price_offer_id', 'status', 'payment_status', 'midtrans_order_id', 'paid_at', 'final_price'];

    protected function casts(): array
    {
        return ['final_price' => 'decimal:2', 'paid_at' => 'datetime'];
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function priceOffer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    // Akses cepat ke penjual lewat relasi service (bukan kolom langsung)
    public function seller()
    {
        return $this->service->seller();
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

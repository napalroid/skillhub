<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'subcategory_id',
        'title',
        'description',
        'price',
        'status',
        'image',
        'portfolio_images',
    ];

    protected $casts = [
        'portfolio_images' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Order::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function priceOffers()
    {
        return $this->hasMany(PriceOffer::class);
    }

    // Scope: query siap-pakai untuk jasa yang sudah disetujui admin
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}

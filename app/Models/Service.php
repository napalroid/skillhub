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
        'booking_config',
        'last_booking_config_edit',
        'booking_enabled',
        'time_slots_enabled',
    ];

    protected $casts = [
        'portfolio_images' => 'array',
        'booking_config' => 'array',
        'last_booking_config_edit' => 'datetime',
        'booking_enabled' => 'boolean',
        'time_slots_enabled' => 'boolean',
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

    public function timeSlots()
    {
        return $this->hasMany(ServiceTimeSlot::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function updateRatingCache()
    {
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->reviews_count = $this->reviews()->count();
        $this->saveQuietly();
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'subcategory_id',
        'title',
        'description',
        'price',
        'status',
        'is_paused',
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
        'is_paused' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function ($service) {
            $service->generateDefaultTimeSlots();
        });
    }

    public function generateDefaultTimeSlots()
    {
        $slots = [];
        $startDate = now()->addDay();
        $daysToGenerate = 60;
        $maxBookingsPerSlot = 5;
        
        for ($i = 0; $i < $daysToGenerate; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            $timeSlots = [
                ['start' => '08:00', 'end' => '09:00'],
                ['start' => '09:00', 'end' => '10:00'],
                ['start' => '10:00', 'end' => '11:00'],
                ['start' => '11:00', 'end' => '12:00'],
                ['start' => '13:00', 'end' => '14:00'],
                ['start' => '14:00', 'end' => '15:00'],
                ['start' => '15:00', 'end' => '16:00'],
                ['start' => '16:00', 'end' => '17:00'],
            ];
            
            foreach ($timeSlots as $slot) {
                $slots[] = [
                    'service_id' => $this->id,
                    'date' => $date->format('Y-m-d'),
                    'time_start' => $slot['start'],
                    'time_end' => $slot['end'],
                    'max_bookings' => $maxBookingsPerSlot,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        ServiceTimeSlot::insert($slots);
    }

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

    public function jokiMlService()
    {
        return $this->hasOne(JokiMlService::class);
    }

    public function barberService()
    {
        return $this->hasOne(BarberService::class);
    }

    public function addons()
    {
        return $this->hasMany(ServiceAddon::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activeAddons()
    {
        return $this->addons()->where('is_active', true);
    }

    /**
     * Returns the first usable seller-uploaded visual for card/list previews.
     * Older services may only have portfolio_images, so use that as a safe
     * fallback instead of showing an empty image placeholder.
     */
    public function getDisplayImagePathAttribute(): ?string
    {
        $candidates = array_filter([
            $this->image,
            ...($this->portfolio_images ?? []),
        ]);

        foreach ($candidates as $candidate) {
            $path = ltrim((string) $candidate, '/');

            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            $path = Str::after($path, 'storage/');
            if ($path !== '' && Storage::disk('public')->exists($path)) {
                return $path;
            }
        }

        return null;
    }

    public function getDisplayImageUrlAttribute(): ?string
    {
        $path = $this->display_image_path;

        if (! $path) {
            return null;
        }

        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : Storage::disk('public')->url($path);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')->where('is_paused', false);
    }
}

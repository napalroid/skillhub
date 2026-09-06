<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTimeSlot extends Model
{
    protected $fillable = [
        'service_id', 'date', 'time_start', 'time_end', 'max_bookings'
    ];

    protected $casts = [
        'date' => 'date',
        'time_start' => 'string',
        'time_end' => 'string',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'time_slot_id');
    }

    public function getAvailableCountAttribute()
    {
        $bookedCount = $this->orders()
            ->whereNotIn('status', ['menunggu_pembayaran', 'dibatalkan'])
            ->count();
        
        return max(0, $this->max_bookings - $bookedCount);
    }

    public function scopeAvailable($query, $date = null)
    {
        if ($date) {
            $query->where('date', $date);
        }

        return $query->whereRaw(
            'max_bookings > (SELECT COUNT(*) FROM orders WHERE orders.time_slot_id = service_time_slots.id AND orders.status NOT IN ("menunggu_pembayaran", "dibatalkan"))'
        );
    }

    public function __toString(): string
    {
        return '';
    }
}


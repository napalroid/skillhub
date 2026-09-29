<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarberOrder extends Model
{
    protected $fillable = [
        'order_id',
        'barber_service_id',
        'selected_haircut_type',
        'selected_additional_services',
        'special_requests',
        'time_slot_id',
    ];

    protected $casts = [
        'selected_additional_services' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function barberService(): BelongsTo
    {
        return $this->belongsTo(BarberService::class);
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(ServiceTimeSlot::class, 'time_slot_id');
    }
}

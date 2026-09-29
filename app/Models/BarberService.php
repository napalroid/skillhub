<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BarberService extends Model
{
    protected $fillable = [
        'service_id',
        'available_haircut_types',
        'estimated_duration_minutes',
        'additional_services',
        'seller_notes',
    ];

    protected $casts = [
        'available_haircut_types' => 'array',
        'additional_services' => 'array',
        'estimated_duration_minutes' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(BarberOrder::class);
    }
}

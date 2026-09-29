<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'icon',
        'description',
        'service_table',
        'order_table',
        'service_model',
        'order_model',
        'has_custom_pricing',
        'requires_manual_approval',
        'hidden_from_listing',
        'enable_time_slots',
        'seller_fields',
        'buyer_fields',
        'is_active',
    ];

    protected $casts = [
        'has_custom_pricing' => 'boolean',
        'requires_manual_approval' => 'boolean',
        'hidden_from_listing' => 'boolean',
        'enable_time_slots' => 'boolean',
        'is_active' => 'boolean',
        'seller_fields' => 'array',
        'buyer_fields' => 'array',
    ];

    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }
}

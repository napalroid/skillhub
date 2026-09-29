<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JokiMlService extends Model
{
    protected $fillable = [
        'service_id',
        'pricing_mode',
        'price_per_star_config',
        'rank_range',
        'special_notes',
        'estimated_completion_days',
    ];

    protected $casts = [
        'price_per_star_config' => 'array',
        'rank_range' => 'array',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(JokiMlOrder::class);
    }
}

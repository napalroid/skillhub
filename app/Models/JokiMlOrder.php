<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class JokiMlOrder extends Model
{
    protected $fillable = [
        'order_id',
        'joki_ml_service_id',
        'current_rank',
        'current_division',
        'current_stars',
        'target_rank',
        'target_division',
        'target_stars',
        'ml_username',
        'ml_password_encrypted',
        'hero_notes',
        'additional_notes',
        'total_stars_needed',
        'auto_calculated_price',
        'seller_manual_price',
        'price_breakdown',
        'current_progress',
        'progress_percentage',
    ];

    protected $casts = [
        'total_stars_needed' => 'integer',
        'auto_calculated_price' => 'decimal:2',
        'seller_manual_price' => 'decimal:2',
        'price_breakdown' => 'array',
        'progress_percentage' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function jokiMlService(): BelongsTo
    {
        return $this->belongsTo(JokiMlService::class);
    }

    public function getMlPasswordAttribute()
    {
        if (!$this->ml_password_encrypted) {
            return null;
        }
        try {
            return Crypt::decryptString($this->ml_password_encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setMlPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['ml_password_encrypted'] = Crypt::encryptString($value);
        }
    }
}

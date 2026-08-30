<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'service_id',
        'user_id', 
        'rating', 
        'comment',
        'is_verified_buyer'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_buyer' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function booted()
    {
        static::created(function ($review) {
            $review->service->updateRatingCache();
        });
        
        static::updated(function ($review) {
            $review->service->updateRatingCache();
        });
        
        static::deleted(function ($review) {
            $review->service->updateRatingCache();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPriceHistory extends Model
{
    protected $fillable = [
        'order_id',
        'changed_by',
        'old_price',
        'new_price',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'old_price' => 'decimal:2',
            'new_price' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    protected $fillable = ['order_id', 'sender_id', 'offered_price', 'status'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
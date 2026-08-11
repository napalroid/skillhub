<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'proof_file', 'amount', 'status', 'verified_by', 'gateway_transaction_id', 'payment_type', 'qris_url', 'gateway_response', 'expires_at'];

    protected function casts(): array
    {
        return ['gateway_response' => 'array', 'expires_at' => 'datetime'];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'balance_before', 'balance_after',
        'reference_type', 'reference_id', 'description', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_REFUND = 'refund';

    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING = 'pending';
    const STATUS_FAILED = 'failed';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        if ($this->reference_type === 'order') {
            return $this->belongsTo(Order::class, 'reference_id');
        }
        return null;
    }

    public function payoutRequest()
    {
        if ($this->reference_type === 'payout_request') {
            return $this->belongsTo(PayoutRequest::class, 'reference_id');
        }
        return null;
    }

    // Alias for backward compatibility
    public function withdrawal()
    {
        return $this->payoutRequest();
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_CREDIT => 'Saldo Masuk',
            self::TYPE_DEBIT => 'Saldo Keluar',
            self::TYPE_WITHDRAWAL => 'Penarikan Dana',
            self::TYPE_REFUND => 'Pengembalian Dana',
            default => ucfirst($this->type),
        };
    }

    public function referenceLabel(): string
    {
        return match ($this->reference_type) {
            'order' => 'Pesanan',
            'payout_request' => 'Penarikan',
            default => $this->reference_type ? ucfirst($this->reference_type) : 'Transaksi',
        };
    }

    public function isPositive(): bool
    {
        return in_array($this->type, [self::TYPE_CREDIT, self::TYPE_REFUND]);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}

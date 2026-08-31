<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscrowTransaction extends Model
{
    protected $fillable = [
        'payment_id',
        'order_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'status',
        'processed_by',
        'processed_at',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'processed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeExpiringSoon($query, int $minutes = 60)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addMinutes($minutes)]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            self::TYPE_IN => 'Masuk',
            self::TYPE_OUT => 'Keluar',
            default => ucfirst($this->type),
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_EXPIRED => 'Kadaluarsa',
            default => ucfirst($this->status),
        };
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isOverdue(): bool
    {
        return $this->isPending() && $this->expires_at && $this->expires_at->isPast();
    }

    public function timeRemaining(): ?int
    {
        if (!$this->expires_at || !$this->isPending()) {
            return null;
        }
        return max(0, now()->diffInSeconds($this->expires_at, false));
    }
}

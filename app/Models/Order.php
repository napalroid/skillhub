<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['service_id', 'buyer_id', 'price_offer_id', 'status', 'payment_status', 'midtrans_order_id', 'paid_at', 'final_price', 'completed_at'];

    protected function casts(): array
    {
        return ['final_price' => 'decimal:2', 'paid_at' => 'datetime', 'completed_at' => 'datetime'];
    }

    // Status kanonik alur escrow
    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';

    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

    public const STATUS_DIBAYAR = 'dibayar';

    public const STATUS_DIKERJAKAN = 'dikerjakan';

    public const STATUS_MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_DIBATALKAN;
    }

    public function canBeStartedBySeller(): bool
    {
        return $this->status === self::STATUS_DIBAYAR;
    }

    public function canBeDelivered(): bool
    {
        return in_array($this->status, [self::STATUS_DIBAYAR, self::STATUS_DIKERJAKAN], true);
    }

    public function canBeCompletedByBuyer(): bool
    {
        return $this->status === self::STATUS_MENUNGGU_PERSETUJUAN;
    }

    public function isEscrowHeld(): bool
    {
        return in_array($this->status, [
            self::STATUS_DIBAYAR,
            self::STATUS_DIKERJAKAN,
            self::STATUS_MENUNGGU_PERSETUJUAN,
            self::STATUS_SELESAI,
        ], true);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function priceOffer()
    {
        return $this->belongsTo(PriceOffer::class);
    }

    // Akses cepat ke penjual lewat relasi service (bukan kolom langsung)
    public function seller()
    {
        return $this->service->seller();
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function files()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

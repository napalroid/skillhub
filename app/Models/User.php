<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'first_name', 'last_name', 'email', 'password', 'phone', 'role', 'balance', 'payout_type', 'payout_account', 'payout_account_name'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function negotiations()
    {
        return $this->hasMany(Negotiation::class, 'sender_id');
    }

    public function orderMessages()
    {
        return $this->hasMany(OrderMessage::class, 'sender_id');
    }

    public function uploadedFiles()
    {
        return $this->hasMany(OrderFile::class, 'uploader_id');
    }

    public function reportsMade()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function reportsReceived()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function notifications()
    {
        return $this->hasMany(UserNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(UserNotification::class)->where('is_read', false);
    }

    public function buyerConversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function sellerConversations()
    {
        return $this->hasMany(Conversation::class, 'seller_id');
    }

    public function priceOffersAsSeller()
    {
        return $this->hasMany(PriceOffer::class, 'seller_id');
    }

    public function priceOffersAsBuyer()
    {
        return $this->hasMany(PriceOffer::class, 'buyer_id');
    }
}

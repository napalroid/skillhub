<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['user_id', 'subcategory_id', 'title', 'description', 'price', 'status'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scope: query siap-pakai untuk jasa yang sudah disetujui admin
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id', 
        'reported_user_id', 
        'order_id', 
        'reporter_role',
        'category',
        'reason', 
        'status',
        'admin_notes'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public static function getCategories()
    {
        return [
            'Seller tidak responsif',
            'Hasil tidak sesuai deskripsi',
            'Pembayaran bermasalah',
            'Penipuan',
            'Konten tidak pantas',
            'Kualitas buruk',
            'Keterlambatan pengerjaan',
            'Komunikasi buruk',
            'Lainnya'
        ];
    }
} 

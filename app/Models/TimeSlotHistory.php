<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlotHistory extends Model
{
    protected $fillable = [
        'order_id', 'old_time_slot_id', 'new_time_slot_id', 'changed_by', 'notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function oldSlot()
    {
        return $this->belongsTo(ServiceTimeSlot::class, 'old_time_slot_id');
    }

    public function newSlot()
    {
        return $this->belongsTo(ServiceTimeSlot::class, 'new_time_slot_id');
    }
}

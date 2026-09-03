<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type',
        'requested_category_name',
        'existing_category_id',
        'requested_subcategory_name',
        'reason_for_request',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function existingCategory()
    {
        return $this->belongsTo(Category::class, 'existing_category_id');
    }
}

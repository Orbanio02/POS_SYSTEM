<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    // ✅ FIX: define product relationship
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // (optional but recommended)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

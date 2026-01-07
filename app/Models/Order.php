<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'payment_status',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    /* Relationships */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // ✅ FIX: this relationship was missing (causing the 500 error)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

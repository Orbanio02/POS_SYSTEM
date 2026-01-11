<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'image',
    ];

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function isLowStock(): bool
    {
        $threshold = $this->low_stock_threshold ?? 5;
        return $this->stock_quantity <= $threshold;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
    }

    /* ============================
       Assigned clients
    ============================ */
    public function clients()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('assigned_by')
            ->withTimestamps();
    }
}

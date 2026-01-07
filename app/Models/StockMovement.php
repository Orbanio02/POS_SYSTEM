<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['product_id','user_id','type','quantity','cost','note'];

    public function product() { return $this->belongsTo(Product::class); }
}

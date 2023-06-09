<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shop_id',
        'product_id',
        'status',
    ];

    public static function catchIdProduct($id){
        return self::query()
        ->select('product_id')
        ->where('order_id','=',$id)
        ->get();
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
    return $this->belongsTo(Product::class);
    }
    
}

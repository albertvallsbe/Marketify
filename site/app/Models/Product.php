<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'tag',
        'price',
        'shop_id'
    ];

    public function category()
    {
        return $this->belongsToMany(Category::class)->withTimeStamps();
    }

    public static function searchAll($search, $order = 'name_asc') {
        $order_explode = explode("_", $order);
        $orderBy = $order_explode[0];
        $orderDirection = $order_explode[1];
    
        return self::query()
            ->where(function ($query) use ($search) {
                $query->where('products.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('tag', 'LIKE', '%' . $search . '%');
            })
            ->where('products.status', 'active')
            ->orderBy($orderBy, $orderDirection)
            ->paginate(18);
        }

    public static function searchSpecific($search, $filter, $order = 'name_asc'){
        $order_explode = explode("_", $order);
        $orderBy = $order_explode[0];
        $orderDirection = $order_explode[1];

        return self::query()
        ->join('category_product', 'category_product.product_id', '=', 'products.id')
        ->select('products.*')
        ->where('products.name', 'LIKE', '%' . $search . '%' )
        ->where('category_product.category_id', '=', $filter)
        ->where('products.status', 'active')
        ->orderBy($orderBy, $orderDirection)
        ->paginate(18);
    }

    public static function productsShop($shopId, $order){
        $order_explode = explode("_", $order);
        $orderBy = $order_explode[0];
        $orderDirection = $order_explode[1];
        return self::query()
        ->where('products.shop_id', $shopId)
        ->where('products.status', 'active')
        ->orderBy($orderBy, $orderDirection)
        ->paginate(18);
    }

    public static function showByIDs($cart){
        return self::query()
        ->whereIn('id',$cart)
        ->get();
    }
    public static function filterCategory($id){
        return self::query()
        ->join('category_product', 'category_product.product_id', '=', 'products.id')
        ->select('products.*')
        ->where('category_product.category_id', '=', $id)
        ->where('products.status', 'active')
        ->paginate(18);
    }
    public function orderItem()
    {
    return $this->hasOne(OrderItem::class);
    }
}

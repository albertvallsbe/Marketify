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
        'price'
    ];
    public function category()
    {
        return $this->belongsToMany(Category::class)->withTimeStamps();
    }

    public static function searchAll($search, $order = 'name_asc'){
        $order_explode = explode("_", $order);
        $orderBy = $order_explode[0];
        $orderDirection = $order_explode[1]; 

        return self::query()
        ->where('products.name', 'LIKE', '%' . $search . '%' )
        ->orWhere('tag','LIKE','%'. $search . '%')
        ->orderBy($orderBy, $orderDirection)
        ->paginate(8);
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
        ->orderBy($orderBy, $orderDirection)
        ->paginate(8); 
    }
    // public static function searchTagCategory($search,$filter,$order){
    //     return self::query()
    //     ->join('category_product', 'products.id', '=', 'category_product.id')
    //     ->join('categories', 'category_product.id', '=', 'categories.id')
    //     ->select('*')
    //     ->where('categories.id', '=' . $filter )
    //     ->where('products.name', 'LIKE', '%' . $search . '%' )
    //     ->orWhere('products.tag','LIKE','%'. $search . '%')
        
    //     ->paginate(8);
    // }



}

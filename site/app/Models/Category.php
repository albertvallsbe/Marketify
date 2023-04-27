<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    public function product(){
        return $this->belongsToMany(Product::class)->withTimeStamps();
    }


    public static function findCategoryOfProduct($product_id){
        return DB::table('category_product')
        ->join('products', 'products.id', '=', 'category_product.product_id')
        ->where('category_product.product_id', $product_id)
        ->value('category_product.category_id');
    }

    public static function findCategoryName($category_id){
        return DB::table('categories')
                ->where('id', $category_id)
                ->value('name');
    }
}

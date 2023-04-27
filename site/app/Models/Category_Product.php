<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category_Product extends Model
{
    use HasFactory;
    
    protected $table = 'category_product';
    
    protected $fillable = [
        'product_id',
        'category_id'
    ];

    
    public static function findCat_ProByProduct($product_id){
        return Category_Product::where('product_id', $product_id)->orderBy('id', 'desc')->firstOrFail();
    }

    public function product()
    {
        return $this->belongsToMany(Product::class,);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
}

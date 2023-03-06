<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Category;

class Product_Category extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsToMany(Product::class);
    }
    public function category(){
        return $this->belongsToMany(Category::class);
    }
}

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
    public function product()
    {
        return $this->belongsToMany(Product::class);
    }
    public function category()
    {
        return $this->belongsToMany(Category::class);
    }
}

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
    public static function selectCategory(){
        return self::query()
        ->select('*')
        ->get();
    }
    
}

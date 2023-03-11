<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'price'
    ];
     public function category(){
        return $this->belongsToMany(Category::class)->withTimeStamps();
    }

    public static function search($search, $order = 'name_asc') {
    $order_explode = explode("_", $order);
    $orderBy = $order_explode[0];
    $orderDirection = $order_explode[1];

    return self::query()
        ->where('name', 'LIKE', '%' . $search . '%')
        ->orderBy($orderBy, $orderDirection)
        ->simplePaginate(8);
    }
}

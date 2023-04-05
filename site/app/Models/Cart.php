<?php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'products'
    ];

    public static function updateCartDB($productsArray) {
        $userId = auth()->id();
        $existingCart = Cart::where('user_id', $userId)->first();
        if (!$existingCart) {
            $existingCart = new Cart();
            $existingCart->user_id = $userId;
        }
        $existingCart->products = json_encode($productsArray);
        $existingCart->save();
    }   

    public static function updateCartClient(){
        $userId = auth()->id();
        $actualCart = Cart::findOrFail($userId);
        $arrayProducts = $actualCart->products;
        return json_encode($arrayProducts);
    }
}
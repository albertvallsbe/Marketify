<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

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

    public static function showCartByUserID($userId){
        $cart = Cart::where('user_id', $userId)->first();
        if (isset($cart->products)) {
            $arrayProducts = $cart->products;
            return json_decode($arrayProducts);
        }else{
            return "[]";
        }
    }
}

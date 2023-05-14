<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Chat;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'products',
        'status',
    ];

    public static function decodeIds($idsString)
    {
    // Eliminar cualquier carácter que no sea un número o coma
    $cleanedIdsString = preg_replace("/[^0-9,]/", "", $idsString);

    // Convertir la cadena de ids en un array
    $productIds = explode(",", $cleanedIdsString);

    // Eliminar cualquier elemento vacío
    $productIds = array_filter($productIds);

    // Devolver el array de ids
    return $productIds;
    }

    public static function updateOrdersDB($productsArray) {
        $userId = auth()->id();
        $existingOrder = Cart::where('user_id', $userId)->first();
        if (!$existingOrder) {
            $existingOrder = new Order();
            $existingOrder->user_id = $userId;
        }
        $existingOrder->products = json_encode($productsArray);
        $existingOrder->save();
    }

    public static function findShopAndCartProducts(){
        $userId = auth()->id();
        $cart = Cart::showCartByUserID($userId);
        $productIds = Order::decodeIds($cart);
        $shops = Shop::all();
        $products = Product::all();
        $productsByShop = array();
        for ($i=0 ; $i< count($shops); $i++) {
            for ($j=0 ; $j< count($productIds); $j++) {
                $product = $products->where('id', $productIds[$j])->first();
                if ($product && $product->shop_id == $shops[$i]->id) {
                    $productsByShop[$i][$j] = $product;
                }
            }
        }

        return $productsByShop;
    }

    public static function getByUserID(){
        $userId = auth()->id();
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function shop()
    {
        return $this->belongsToMany(Shop::class)->withTimeStamps();
    }

    public function user()
    {
        return $this->belongsToMany(User::class)->withTimeStamps();
    }

    public function chat()
    {
        return $this->hasOne(Chat::class, 'order_id');
    }
}

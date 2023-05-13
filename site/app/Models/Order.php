<?php

namespace App\Models;

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

    protected $casts = [
        'products' => 'array',
    ];

    protected $enumStatus = [
        'pending' => 0,
        'processing' => 1,
        'completed' => 2,
    ];

    public function getStatusAttribute($value)
    {
        return array_search($value, $this->enumStatus, true);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $this->enumStatus[$value];
    }

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

    public static function getByChatID($chat_id) {
        $chat = Chat::where('id', $chat_id)->first();
        return $chat;
    }

    public static function updateOrdersDB($productsArray) {
        $userId = auth()->id();
        $existingOrder = Cart::where('user_id', $userId)->first();
        // dd($existingOrder);
        if (!$existingOrder) {
            $existingOrder = new Order();
            $existingOrder->user_id = $userId;
        }
        $existingOrder->products = json_encode($productsArray);
        $existingOrder->save();
        // dd($existingOrder);
    }

    public static function findShopAndCartProducts(){
        $userId = auth()->id();
        //coge el usuario
        $cart = Cart::showCartByUserID($userId);
        $productIds = Order::decodeIds($cart);
        $shops = Shop::all();
        $products = Product::all();
        $productsByShop = array();
        // $shopName = array();
        for ($i=0 ; $i< count($shops); $i++) {
            $firstProduct = true;
            // $shopName[$i] = $shops[$i]->shopname;
            for ($j=0 ; $j< count($productIds); $j++) {
                $product = $products->where('id', $productIds[$j])->first();
                if ($product && $product->shop_id == $shops[$i]->id) {
                    $productsByShop[$i][$j] = $product;
                    if($firstProduct){
                        $seller_id = $shops[$i]->user_id;
                        $customer_id = auth()->id();
                        $chat = Chat::chatChecker($seller_id, $customer_id);
                        if($chat === null){
                            $chat = Chat::create([
                                'seller_id' => $seller_id,
                                'customer_id' => $customer_id
                            ]);
                        }else{
                            $order = Order::getByChatID($chat->id);
                            $order->update([
                                'status' => 'pending'
                            ]);
                        }

                    $message = Message::create([
                        'chat_id' => $chat->id,
                        'sender_id' => $customer_id,
                        'automatic' => true,
                        'content' => 'Order #XXX has been confirmed. Seller must accept payment and send the products.'
                    ]);
                    $notification = Notification::create([
                        'user_id' => $seller_id,
                        'chat_id' => $chat->id,
                        'read' => false
                    ]);
                    $firstProduct = false;
                    }
                }
            }
        }

        return $productsByShop;
    }

    public function shop()
    {
        // return $this->belongsTo(Shop::class);
        return $this->belongsToMany(Shop::class)->withTimeStamps();
    }

    public function user()
    {
        // return $this->belongsTo(User::class);
        return $this->belongsToMany(User::class)->withTimeStamps();
    }
}

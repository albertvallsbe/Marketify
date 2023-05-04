<?php

namespace App\Models;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopname',
        'username',
        'nif',
        'user_id',
        'logo',
        'url',
        'header_color',
        'order'
    ];    
    
    public static function showByURL($url){
        $shop = Shop::where('url', $url)->orderBy('id', 'desc')->firstOrFail();
        return $shop;
    }

    public static function findShopByURL($url){
        $shop = Shop::where('url', $url)->orderBy('id', 'desc')->first();
        if (!$shop) {
            return "";
        }else{
            if ($shop->id == Shop::findShopUserID(auth()->id())){
                return "";
            }else{
        return $shop;
            }
        }
    }

    public static function findShopName($shop_id){
        return DB::table('shops')
                ->where('id', $shop_id)
                ->value('shopname');
    }
    
    public static function findHeaderShopColor($shop_id){
        return DB::table('shops')
                ->where('id', $shop_id)
                ->value('header_color');
    }

    public static function findBackgroundShopColor($shop_id){
        return DB::table('shops')
                ->where('id', $shop_id)
                ->value('background_color');
    }

    public static function findShopUserID($user_id){
        return DB::table('shops')
                ->orderBy('id', 'DESC')
                ->where('user_id', $user_id)
                ->value('id');
    }

    public static function makeUsercustomer($user_id){
        $user = User::find($user_id);
        $user->role = "seller";
        $user->save();
    }



    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class);
    // }

    public static function generateURL($shop_name) {
        $shop_name = strtolower(str_replace(' ', '-', $shop_name));
        $shop_name = preg_replace('/[^A-Za-z0-9\-]/', '', $shop_name);
        if (Shop::findShopByURL($shop_name) == "") {
            return $shop_name;
        }else{
            return "";
        }
      }
}

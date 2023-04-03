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
        'name',
        'nif',
        'user_id',
    ];

    public static function checkUser($user_id){
        return DB::table('shops')
                ->orderBy('id', 'DESC')
                ->where('user_id', $user_id)
                ->value('id');
    }
    
    public static function makeUserShopper($user_id){
        $user = User::find($user_id);
        $user->role = "seller";
        $user->save();
    }
    
    

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}

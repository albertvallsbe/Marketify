<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use App\Classes\Order;
use Illuminate\Support\Str;
use GuzzleHttp\Psr7\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'name',
        'password',
        'expires_at',
        'email_verified_at',
        'role' ,
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function catchId($valor)
    {
        $sql = "SELECT id FROM users WHERE email = '$valor'";
        $user_id = DB::select($sql);
        return $user_id;
    }

    public static function updatePassword($id, $value)
    {
        foreach ($id as $user) {
            $users = User::find($user->id);
            $users->password =  Hash::make($value);
            return $users;
        }
    }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}

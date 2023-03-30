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

    public function checkUser(){
        return DB::table('shop')
                ->where('user_id', $user_id)
                ->exists();
    }
    
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
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
        'status' => 'boolean',
    ];

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

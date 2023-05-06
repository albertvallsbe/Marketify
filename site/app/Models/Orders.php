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

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

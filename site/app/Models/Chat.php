<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    protected $fillable = [
        'seller_id',
        'customer_id',
        'paymentDone',
        'shipmentSend',
        'shipmentDone'
    ];
    public static function getByUserID(){
        $userId = auth()->id();
        return self::where('seller_id', $userId)
            ->orWhere('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function chatChecker($sellerId, $customerId){
        return self::where(function ($query) use ($sellerId, $customerId) {
            $query->where('seller_id', $sellerId)
                  ->where('customer_id', $customerId);
        })
        ->orWhere(function ($query) use ($sellerId, $customerId) {
            $query->where('seller_id', $customerId)
                  ->where('customer_id', $sellerId);
        })
        ->first();
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function notification()
    {   
        return $this->hasOne(Notification::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }    

    public function getMessagesById($chatId)
    {
        return $this->findOrFail($chatId)->messages;
    }
    
}
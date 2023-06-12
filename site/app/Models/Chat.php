<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'customer_id',
        'order_id',
        'status'
    ];
    public static function getByUserID(){
        $userId = auth()->id();
        return self::where('seller_id', $userId)
            ->orWhere('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function createChatByOrder($shop, $order_id){
            $seller_id = $shop->user_id;
            $customer_id = auth()->id();
                $chat = Chat::create([
                    'seller_id' => $seller_id,
                    'customer_id' => $customer_id,
                    'order_id' => $order_id
                ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $customer_id,
            'automatic' => true,
            'content' => "Order #$order_id has been confirmed. Seller must accept payment and send the products."
        ]);
        $notification = Notification::create([
            'user_id' => $seller_id,
            'chat_id' => $chat->id,
            'read' => false
        ]);
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
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

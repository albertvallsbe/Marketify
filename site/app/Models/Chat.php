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
    ];
    public static function getByUserID(){
        $userId = auth()->id();
        return self::where('seller_id', $userId)
            ->orWhere('customer_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
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
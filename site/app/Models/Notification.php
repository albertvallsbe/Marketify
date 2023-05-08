<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'chat_id',
        'read',
    ];

    public static function showbyChatID($chatId) {
        return static::where('chat_id', $chatId)
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->first();
    }
    
    public function markAsRead($chatId) {
        $notification = static::where('chat_id', $chatId)
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->first();
    
        if ($notification) {
            $notification->read = true;
            $notification->save();
        }
    }
    
    public static function unreadCountForCurrentUser() {
        $userId = auth()->id();
        return static::where('user_id', $userId)
                     ->where('read', false)
                     ->get()
                     ->count();
    }
    

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}

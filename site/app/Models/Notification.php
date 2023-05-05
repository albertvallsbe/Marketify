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

        public function markAsRead()
    {
        if ($this->user_id === auth()->id()) {
            $this->read = true;
            $this->save();
        }
    }

    public static function unreadCountForCurrentUser()
{
    $userId = auth()->id();
    return static::where('user_id', $userId)
                 ->where('read', false)
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

<?php

namespace App\Models;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'content',
    ];


    public function chat()
    {
        return $this->belongsTo(chat::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'item_id',
        'sender_id',
        'receiver_id',
        'content',
        'image_path',
        'is_read',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

}

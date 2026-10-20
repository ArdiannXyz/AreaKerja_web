<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BantuanChat extends Model
{
    use HasFactory;

    protected $table = 'bantuan_chats';

    protected $fillable = [
        'user_id',
        'subjek',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(BantuanMessage::class, 'bantuan_chat_id', 'id')->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(BantuanMessage::class, 'bantuan_chat_id', 'id')->latestOfMany();
    }

    public function unreadMessagesForAdmin()
    {
        return $this->hasMany(BantuanMessage::class, 'bantuan_chat_id', 'id')
            ->where('sender_type', 'user')
            ->where('is_read', false);
    }

    public function unreadMessagesForUser()
    {
        return $this->hasMany(BantuanMessage::class, 'bantuan_chat_id', 'id')
            ->where('sender_type', 'admin')
            ->where('is_read', false);
    }
}


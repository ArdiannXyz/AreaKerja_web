<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BantuanMessage extends Model
{
    use HasFactory;

    protected $table = 'bantuan_messages';

    protected $fillable = [
        'bantuan_chat_id',
        'sender_id',
        'sender_type',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function chat()
    {
        return $this->belongsTo(BantuanChat::class, 'bantuan_chat_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}


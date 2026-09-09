<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'status',
        'title',
        'kuota',
        'image',
        'content',
        'tgl_mulai',
        'jam_mulai',
        'tgl_akhir',
        'jam_akhir',
        'lokasi',
        'link_form',
        'penutupan_pendaftaran',
    ];

    public function kegiatan()
    {
        return $this->hasMany(KegiatanEvent::class, 'event_id');
    }

    public function participants()
    {
        return $this->hasMany(\App\Models\EventParticipant::class, 'event_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Perusahaan::class, 'event_participants', 'event_id', 'user_id', 'id', 'user_id');
    }
}

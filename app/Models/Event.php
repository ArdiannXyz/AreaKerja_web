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

    protected $appends = ['is_ended'];

    public function getIsEndedAttribute(): bool
    {
        return $this->status === 'tutup' || ($this->tgl_akhir && now()->toDateString() > $this->tgl_akhir);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'buka')
            ->where(function ($q) {
                $q->whereNull('tgl_akhir')
                  ->orWhere('tgl_akhir', '>=', now()->toDateString());
            });
    }

    public function scopeEnded($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'tutup')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('tgl_akhir')
                      ->where('tgl_akhir', '<', now()->toDateString());
              });
        });
    }

    public static function autoCloseExpired(): void
    {
        static::where('status', 'buka')
            ->whereNotNull('tgl_akhir')
            ->where('tgl_akhir', '<', now()->toDateString())
            ->update(['status' => 'tutup']);
    }

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

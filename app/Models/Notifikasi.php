<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';
    protected $fillable = [
        'user_id',
        'perusahaan_id',
        'pelamar_lowongan_id',
        'judul',
        'pesan',
        'expired_at',
        // 'is_read' → TIDAK dimasukkan, hanya boleh diubah via endpoint tandai-dibaca
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function pelamarLowongan()
    {
        return $this->belongsTo(PelamarLowongan::class, 'pelamar_lowongan_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}

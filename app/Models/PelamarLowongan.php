<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelamarLowongan extends Model
{
    use HasFactory;

    protected $table = 'pelamar_lowongans';
    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'status',
    ];

    public function lowongan_perusahaan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_id');
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id' );
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'pelamar_lowongan_id');
    }
}

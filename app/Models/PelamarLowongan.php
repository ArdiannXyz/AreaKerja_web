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
        'alasan_penolakan',
    ];

    /** Relasi utama ke lowongan perusahaan (canonical) */
    public function lowonganPerusahaan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_id');
    }

    /** Alias untuk backward compatibility — gunakan lowonganPerusahaan() */
    public function lowongan_perusahaan()
    {
        return $this->lowonganPerusahaan();
    }

    /** Alias untuk backward compatibility — gunakan lowonganPerusahaan() */
    public function lowongan()
    {
        return $this->lowonganPerusahaan();
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'pelamar_lowongan_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliKandidat extends Model
{
    use HasFactory;

    protected $table = 'pembeli_kandidats';

    protected $fillable = [
        'no_referensi',
        'pelamar_id',
        'lowongan_perusahaan_id',
        'status',
        'alasan_penolakan',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function lowonganPerusahaan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_perusahaan_id');
    }

    public function catatanKoin()
    {
        return $this->hasOne(CatatanKoin::class, 'no_referensi', 'no_referensi');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliKandidat extends Model
{
    use HasFactory;
    protected $table = 'pembeli_kandidats';
    protected $fillable = [
        'pelamar_id',
        'lowongan_perusahaan_id',
        'no_referensi',
        // 'status'           → TIDAK dimasukkan, diubah oleh kandidat via updateStatus()
        // 'alasan_penolakan' → TIDAK dimasukkan, diubah oleh kandidat via updateStatus()
    ];


    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowonganPerusahaan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_perusahaan_id');
    }

    public function catatanKoin()
    {
        return $this->hasOne(CatatanKoin::class, 'no_referensi','no_referensi');
    }
    
}

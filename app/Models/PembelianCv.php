<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianCv extends Model
{
    use HasFactory;

    protected $table = 'pembelian_cvs';

    protected $fillable = [
        'perusahaan_id',
        'pelamar_id',
        'koin',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }
}


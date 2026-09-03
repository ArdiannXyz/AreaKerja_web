<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'alamat_perusahaan';

    protected $fillable = [
        'perusahaan_id',
        'label',
        'provinsi',
        'kota',
        'kecamatan',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'desa',
        'kode_pos',
        'detail',
        'utama',
    ];

    protected $casts = [
        'utama' => 'boolean',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }
}

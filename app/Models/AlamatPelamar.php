<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPelamar extends Model
{
    use HasFactory;

    protected $table = 'alamatpelamars';

    protected $fillable = [
        'pelamar_id',
        'label',
        'provinsi',
        'kota',
        'kecamatan',
        'desa',
        'kode_pos',
        'detail',
        'is_primary',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id', 'id');
    }
}

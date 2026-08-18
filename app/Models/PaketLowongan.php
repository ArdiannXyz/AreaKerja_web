<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketLowongan extends Model
{
    use HasFactory;

    protected $table = 'paket_lowongans';

    protected $fillable = [
        'nama',
        'harga_koin',
        'batas_listing',
        'deskripsi',
        'benefit',
    ];

    protected $casts = [
        'harga_koin'    => 'integer',
        'batas_listing' => 'integer',
    ];

    public function lowonganPerusahaans()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'paket_id', 'id');
    }
}

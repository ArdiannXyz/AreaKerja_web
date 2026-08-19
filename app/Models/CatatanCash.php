<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanCash extends Model
{
    use HasFactory;

    protected $table = 'catatan_cashs';

    protected $fillable = [
        'user_id',
        'no_referensi',
        'daftar_bank_id',
        'pesanan',
        'dari',
        'sumberDana',
        'total',
        'status',
        'bukti',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getHargaPembayaranAttribute()
    {
        return (object)[
            'id'          => 1,
            'nama'        => $this->pesanan ?? 'Top Up Koin Area Kerja',
            'harga'       => $this->total ?? 100000,
            'jumlah_koin' => 100,
        ];
    }

    public function bank()
    {
        return $this->belongsTo(DaftarBank::class, 'daftar_bank_id');
    }

    public function perusahaan()
    {
        return $this->user?->perusahaan;
    }
}

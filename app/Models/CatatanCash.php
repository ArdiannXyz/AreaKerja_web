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
        'harga_pembayaran_id',
        'daftar_bank_id',
        'bukti_transfer',
        'nominal',
        'keterangan',
        // 'status' → TIDAK dimasukkan, hanya Finance yang boleh ubah
    ];


     protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hargaPembayaran()
    {
        return $this->belongsTo(HargaPembayaran::class, 'harga_pembayaran_id');
    }

    public function bank()
    {
        return $this->belongsTo(DaftarBank::class, 'daftar_bank_id');
    }

    public function perusahaan()
{
    return $this->user->perusahaan; 
}


}

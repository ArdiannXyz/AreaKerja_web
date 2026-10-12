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
        $pesananLower = strtolower($this->pesanan ?? '');
        $isTopUp = str_contains($pesananLower, 'koin') || str_contains($pesananLower, 'top up');

        $jumlahKoin = 0;
        if ($isTopUp) {
            $jumlahKoin = 100;
            if (preg_match('/(\d+)\s*koin/i', $this->pesanan ?? '', $matches)) {
                $jumlahKoin = (int) $matches[1];
            } elseif ($this->total >= 500000) {
                $jumlahKoin = 1000;
            } elseif ($this->total >= 100000) {
                $jumlahKoin = 100;
            } elseif ($this->total >= 10000) {
                $jumlahKoin = 10;
            }
        }

        return (object)[
            'id'          => 1,
            'nama'        => $this->pesanan ?? ($isTopUp ? 'Top Up Koin Area Kerja' : 'Pembayaran'),
            'harga'       => $this->total ?? 100000,
            'jumlah_koin' => $jumlahKoin,
        ];
    }

    public function bank()
    {
        return $this->belongsTo(DaftarBank::class, 'daftar_bank_id');
    }

    /**
     *Accessor: mengambil perusahaan melalui relasi user.
     * Bukan relasi Eloquent langsung — hanya shortcut convenience.
     */
    public function getPerusahaanAttribute()
    {
        return $this->user?->perusahaan;
    }
}

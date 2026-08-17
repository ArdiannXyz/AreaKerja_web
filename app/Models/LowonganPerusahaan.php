<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganPerusahaan extends Model
{
    use HasFactory;
    protected $table = 'lowongan_perusahaans';
    protected $fillable = [
        'perusahaan_id',
        'nama',
        'slug',
        'jenis',
        'gaji_awal',
        'gaji_akhir',
        'label_gaji',
        'deskripsi',
        'alamat',
        'kategori',
        'batas_lamaran',
        'syarat_pekerjaan',
        'tanggung_jawab',
        'benefit',
        'paket_id',
        // Field berikut SENGAJA TIDAK dimasukkan karena dikelola sistem:
        // 'published_at'   → diatur oleh controller saat publish lowongan
        // 'expired_at'     → dihitung otomatis dari paket
        // 'boosted_until'  → diatur oleh fitur boost berbayar
        // 'rekomendasi'    → diatur oleh SuperAdmin saja
        // 'last_activity'  → diperbarui otomatis oleh model event
    ];


    public function resolveRouteBinding($value, $field = null)
    {
        // Hanya redirect pada GET
        if (request()->method() === 'GET' && is_numeric($value)) {

            $item = $this->with('perusahaan')->findOrFail($value);

            return abort(301, '', [
                'Location' => route(
                    request()->route()->getName(),
                    [
                        'perusahaan' => $item->perusahaan->slug,
                        'lowongan'   => $item->slug,
                    ]
                )
            ]);
        }

        // PUT / POST / DELETE → jangan redirect
        return $this->where('slug', $value)->orWhere('id', $value)->firstOrFail();
    }



    public function touchActivity()
    {
        $this->update(['last_activity' => now()]);
    }

    protected static function booted()
    {
        static::saving(function ($lowongan) {
            $lowongan->last_activity = now();
        });
    }


    public function paket()
    {
        return $this->belongsTo(PaketLowongan::class, 'paket_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function simpanLowongans()
    {
        return $this->hasMany(SimpanLowongan::class, 'lowongan_id');
    }

    public function lowongan_pelamar()
    {
        return $this->belongsToMany(PelamarLowongan::class, 'pelamar_lowongans', 'lowongan_id', 'pelamar_id');
    }

    public function pelamar()
    {
        return $this->belongsToMany(Pelamar::class, 'pelamar_lowongans', 'lowongan_id', 'pelamar_id')->withPivot('status', 'created_at', 'updated_at');
    }

    public function pembelianKandidat()
    {
        return $this->hasMany(PembeliKandidat::class, 'lowongan_perusahaan_id');
    }

    protected $casts = [
        'published_at' => 'datetime',
        'expired_at'   => 'datetime',
        'batas_lamaran' => 'date',
    ];

    public function getIsExpiredAttribute()
    {
        return $this->batas_lamaran
            ? Carbon::parse($this->batas_lamaran)->endOfDay()->isPast()
            : false;
    }
}

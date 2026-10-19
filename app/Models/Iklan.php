<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Iklan extends Model
{
    use HasFactory;

    protected $table = 'iklans';

    protected $fillable = [
        'perusahaan_id',
        'user_id',
        'judul_iklan',
        'gambar_banner',
        'url_tujuan',
        'paket_nama',
        'durasi_hari',
        'koin_terpotong',
        'posisi',
        'status',
        'alasan_penolakan',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_views',
        'total_clicks',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'durasi_hari' => 'integer',
        'koin_terpotong' => 'integer',
        'total_views' => 'integer',
        'total_clicks' => 'integer',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk mengambil iklan yang sedang tayang
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
                     ->whereNotNull('tanggal_mulai')
                     ->where('tanggal_mulai', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', now());
                     });
    }

    /**
     * Helper untuk cek apakah iklan masih aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif'
            && $this->tanggal_mulai
            && $this->tanggal_mulai <= now()
            && (!$this->tanggal_selesai || $this->tanggal_selesai >= now());
    }

    /**
     * Sisa hari tayang
     */
    public function getSisaHariAttribute(): ?int
    {
        if (!$this->tanggal_selesai) {
            return null;
        }

        if (now()->gt($this->tanggal_selesai)) {
            return 0;
        }

        return (int) now()->diffInDays($this->tanggal_selesai, false);
    }

    /**
     * URL Gambar Banner
     */
    public function getBannerUrlAttribute(): string
    {
        if (empty($this->gambar_banner)) {
            return asset('images/auth_banner.png');
        }

        if (str_starts_with($this->gambar_banner, 'http://') || str_starts_with($this->gambar_banner, 'https://')) {
            return $this->gambar_banner;
        }

        if (str_starts_with($this->gambar_banner, 'images/')) {
            if (file_exists(public_path($this->gambar_banner))) {
                return asset($this->gambar_banner);
            }
        }

        if (file_exists(public_path('storage/' . $this->gambar_banner))) {
            return asset('storage/' . $this->gambar_banner);
        }

        if (file_exists(public_path($this->gambar_banner))) {
            return asset($this->gambar_banner);
        }

        return asset('images/auth_banner.png');
    }
}

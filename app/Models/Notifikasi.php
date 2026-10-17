<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasis';
    protected $fillable = [
        'user_id',
        'perusahaan_id',
        'pelamar_lowongan_id',
        'lowongan_id',
        'judul',
        'pesan',
        'is_read',
        'expired_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelamarLowongan()
    {
        return $this->belongsTo(PelamarLowongan::class, 'pelamar_lowongan_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_id');
    }

    /**
     * Tentukan URL tujuan saat notifikasi diklik
     */
    public function getUrlAttribute(): ?string
    {
        $userRole = strtolower((string) optional($this->user)->role);

        // 1. Notifikasi undangan kerja / rekrutmen kandidat untuk Pelamar
        if (stripos($this->judul, 'rekrut') !== false || stripos($this->pesan, 'merekrut') !== false || stripos($this->judul, 'tawaran') !== false) {
            $perusahaan = $this->perusahaan ?? ($this->lowongan ? $this->lowongan->perusahaan : null);
            $lowongan = $this->lowongan;

            if ($perusahaan && $lowongan) {
                return route('kandidat.detailTawaran', [
                    'perusahaan' => $perusahaan->slug ?? $perusahaan->id,
                    'lowongan'   => $lowongan->slug ?? $lowongan->id,
                ]);
            }

            // Jika lowongan_id belum terisi di notifikasi, cari tautan PelamarLowongan
            if ($this->user_id) {
                $pelamar = $this->user?->pelamar;
                if ($pelamar) {
                    $plQuery = PelamarLowongan::where('pelamar_id', $pelamar->id);
                    if ($this->lowongan_id) {
                        $plQuery->where('lowongan_id', $this->lowongan_id);
                    }
                    if ($this->perusahaan_id) {
                        $plQuery->whereHas('lowonganPerusahaan', fn($q) => $q->where('perusahaan_id', $this->perusahaan_id));
                    }
                    $pl = $plQuery->latest()->with('lowonganPerusahaan.perusahaan')->first();

                    if ($pl && $pl->lowonganPerusahaan) {
                        $pModel = $pl->lowonganPerusahaan->perusahaan;
                        return route('kandidat.detailTawaran', [
                            'perusahaan' => $pModel?->slug ?? $pl->lowonganPerusahaan->perusahaan_id,
                            'lowongan'   => $pl->lowonganPerusahaan->slug ?? $pl->lowongan_id,
                        ]);
                    }
                }
            }

            return route('pelamar.tawaran');
        }

        // 2. Notifikasi untuk Perusahaan
        if ($userRole === 'perusahaan') {
            if ($this->lowongan) {
                return route('perusahaan.pelamar', $this->lowongan->slug ?? $this->lowongan->id);
            }
            if ($this->lowongan_id) {
                $l = LowonganPerusahaan::find($this->lowongan_id);
                if ($l) {
                    return route('perusahaan.pelamar', $l->slug ?? $l->id);
                }
            }
        }

        // 3. Notifikasi terkait lowongan pekerjaan untuk Pelamar / Umum
        if ($this->lowongan) {
            $p = $this->lowongan->perusahaan ?? $this->perusahaan;
            if ($p) {
                return route('detail.lowongan.non.user', [
                    'perusahaan' => $p->slug ?? $p->id,
                    'lowongan'   => $this->lowongan->slug ?? $this->lowongan->id,
                ]);
            }
        }

        if ($this->lowongan_id) {
            $l = LowonganPerusahaan::with('perusahaan')->find($this->lowongan_id);
            if ($l && $l->perusahaan) {
                return route('detail.lowongan.non.user', [
                    'perusahaan' => $l->perusahaan->slug ?? $l->perusahaan->id,
                    'lowongan'   => $l->slug ?? $l->id,
                ]);
            }
        }

        // 4. Notifikasi terkait status lamaran kerja
        if ($this->pelamar_lowongan_id) {
            $pl = PelamarLowongan::with('lowonganPerusahaan.perusahaan')->find($this->pelamar_lowongan_id);
            if ($pl && $pl->lowonganPerusahaan && $pl->lowonganPerusahaan->perusahaan) {
                return route('detail.lowongan.non.user', [
                    'perusahaan' => $pl->lowonganPerusahaan->perusahaan->slug ?? $pl->lowonganPerusahaan->perusahaan->id,
                    'lowongan'   => $pl->lowonganPerusahaan->slug ?? $pl->lowonganPerusahaan->id,
                ]);
            }
        }

        return null;
    }
}

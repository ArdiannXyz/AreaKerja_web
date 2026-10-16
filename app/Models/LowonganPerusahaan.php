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
        'status',
        'batas_lamaran',
        'syarat_pekerjaan',
        'tanggung_jawab',
        'benefit',
        'paket_id',
        'published_at',
        'expired_at',
        'boosted_until',
        'rekomendasi',
    ];

    protected $casts = [
        'published_at'  => 'datetime',
        'expired_at'    => 'datetime',
        'boosted_until' => 'datetime',
        'batas_lamaran' => 'date',
    ];

    public function paket()
    {
        return $this->belongsTo(PaketLowongan::class, 'paket_id', 'id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id', 'id');
    }

    public function pelamar()
    {
        return $this->belongsToMany(Pelamar::class, 'pelamar_lowongans', 'lowongan_id', 'pelamar_id')
                    ->withPivot('status', 'created_at', 'updated_at');
    }

    public function getIsExpiredAttribute()
    {
        if ($this->expired_at && $this->expired_at->isPast()) {
            return true;
        }
        if ($this->batas_lamaran && Carbon::parse($this->batas_lamaran)->endOfDay()->isPast()) {
            return true;
        }
        return false;
    }

    public function getTodayApplicantsCountAttribute(): int
    {
        return PelamarLowongan::where('lowongan_id', $this->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'saved')
            ->count();
    }

    public function isDailyLocked(): bool
    {
        $perusahaan = $this->perusahaan;
        $isVip = $perusahaan && $perusahaan->isVip();
        if ($isVip) {
            return false;
        }

        return $this->today_applicants_count >= 5;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamars';
    protected $fillable = [
        'user_id',
        'nama_pelamar',
        'telepon_pelamar',
        'gender',
        'tanggal_lahir',
        'deskripsi_diri',
        'img_profile',
        'gaji_minimal',
        'gaji_maksimal',
        'divisi',
        // Field berikut SENGAJA TIDAK dimasukkan karena dikelola sistem:
        // 'kategori'         → diubah oleh Finance saat verifikasi pembayaran
        // 'mulai_pelatihan'  → diubah oleh Admin/SuperAdmin
        // 'selesai_pelatihan' → diubah oleh Admin/SuperAdmin
    ];

    protected $casts = [
        'divisi' => 'array',
    ];

    public function getDivisiAttribute($value)
    {
        // kalau kosong → array kosong
        if (!$value) return [];

        // 1. Bersihkan karakter escape yang rusak (misal: \" )
        $clean = str_replace(['\\"', '\\\''], ['"', "'"], $value);

        // 2. Coba decode JSON
        $decoded = json_decode($clean, true);

        // 3. Kalau decode berhasil dan hasilnya array → return
        if (is_array($decoded)) {
            return $decoded;
        }

        // 4. Jika decode tetap gagal → fallback:
        //    hilangkan bracket [ ] lalu pecah manual berdasarkan koma
        $fallback = trim($clean, "[]");

        // jika hasil kosong → return array kosong
        if (!$fallback) return [];

        // pisahkan berdasarkan koma
        $items = array_map(function ($item) {
            return trim($item, "\"' ");
        }, explode(',', $fallback));

        return array_filter($items); // hilangkan kosong
    }



    public function getUmurAttribute()
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    public function getGenderSingkatAttribute()
    {
        if (strtolower($this->gender) == 'laki-laki') {
            return 'L';
        }
        if (strtolower($this->gender) == 'perempuan') {
            return 'P';
        }

        return $this->gender;
    }

    public function isProfileComplete()
    {
        return !(
            empty($this->nama_pelamar) ||
            empty($this->img_profile) ||
            empty($this->gender) ||
            empty($this->tanggal_lahir) ||
            empty($this->deskripsi_diri) ||
            empty($this->gaji_minimal) ||
            empty($this->gaji_maksimal)
        );
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alamat_pelamar()
    {
        return $this->hasMany(AlamatPelamar::class, 'pelamar_id');
    }

    public function riwayat_pendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pelamar_id');
    }

    public function pengalaman_organisasi()
    {
        return $this->hasMany(Organisasi::class, 'pelamar_id');
    }

    public function pengalaman_kerja()
    {
        return $this->hasMany(PengalamanKerja::class, 'pelamar_id');
    }

    public function skill()
    {
        return $this->hasMany(Skill::class, 'pelamar_id');
    }

    public function sosmed()
    {
        return $this->hasOne(SocialMediaPelamar::class, 'pelamar_id');
    }

    public function simpanLowongans()
    {
        return $this->hasMany(SimpanLowongan::class, 'pelamar_id');
    }

    public function pembelianKandidat()
    {
        return $this->hasMany(PembeliKandidat::class, 'pelamar_id');
    }

    public function divisi_pelamars()
    {
        return $this->hasMany(DivisiPelamar::class, 'pelamar_id');
    }


    // public function pelamar_lowongan()
    // {
    //     return 
    // }


    public function lowongans()
    {
        return $this->belongsToMany(LowonganPerusahaan::class, 'pelamar_lowongans', 'pelamar_id', 'lowongan_id')->withPivot('status')->withTimestamps();
    }
    public function isCvComplete()
    {
        return $this->riwayat_pendidikan()->exists()
            && $this->pengalaman_kerja()->exists()
            && $this->skill()->exists()
            && $this->pengalaman_organisasi()->exists()
            && $this->sosmed()->exists();
    }
}

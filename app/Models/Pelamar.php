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
        'alamat',
        'kota',
        'provinsi',
        'skills',
        'social_links',
        'resume_file',
        'img_profile',
        'gaji_minimal',
        'gaji_maksimal',
        'divisi',
        'kategori',
        'mulai_pelatihan',
        'selesai_pelatihan',
    ];

    protected $casts = [
        'skills'            => 'array',
        'social_links'      => 'array',
        'divisi'            => 'array',
        'tanggal_lahir'     => 'date',
        'mulai_pelatihan'   => 'datetime',
        'selesai_pelatihan' => 'datetime',
    ];

    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->age : null;
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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function riwayat_pendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'pelamar_id', 'id');
    }

    public function pengalaman_kerja()
    {
        return $this->hasMany(PengalamanKerja::class, 'pelamar_id', 'id');
    }

    public function lowongans()
    {
        return $this->belongsToMany(LowonganPerusahaan::class, 'pelamar_lowongans', 'pelamar_id', 'lowongan_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function simpanLowongans()
    {
        return $this->hasMany(PelamarLowongan::class, 'pelamar_id', 'id')->where('status', 'saved');
    }

    public function isCvComplete()
    {
        return $this->riwayat_pendidikan()->exists()
            && $this->pengalaman_kerja()->exists()
            && !empty($this->skills);
    }

    public function pengalaman_organisasi()
    {
        return $this->hasMany(PengalamanOrganisasi::class, 'pelamar_id', 'id');
    }

    public function getSkillAttribute()
    {
        return collect($this->skills ?? [])->values()->map(function ($item, $index) {
            $id = $index + 1;
            if (is_array($item)) {
                return (object)array_merge(['id' => $id], $item);
            }
            return (object)['id' => $id, 'skill' => (string)$item, 'experience_level' => 'Menengah'];
        });
    }

    public function alamat_pelamar()
    {
        return $this->hasMany(AlamatPelamar::class, 'pelamar_id', 'id');
    }

    public function getAlamatPelamarAttribute()
    {
        try {
            $addresses = $this->alamat_pelamar()->get();
            if ($addresses->isNotEmpty()) {
                return $addresses;
            }
        } catch (\Throwable $e) {}

        $user = $this->user ?? auth()->user();
        $prov = $user?->provinsi_id ?? $this->provinsi;
        $kota = $user?->kota_id ?? $this->kota;

        if (!$prov && !$kota && !$this->alamat) {
            return collect();
        }

        return collect([
            (object)[
                'id'        => 1,
                'label'     => 'Alamat Utama',
                'desa'      => $this->alamat ?? 'Alamat Belum Diisi',
                'detail'    => $this->alamat ?? 'Alamat Belum Diisi',
                'kecamatan' => $this->kota ?? '-',
                'kota'      => $this->kota ?? '-',
                'provinsi'  => $this->provinsi ?? '-',
                'kode_pos'  => '60111',
            ]
        ]);
    }

    public function getSosmedAttribute()
    {
        $links = $this->social_links ?? [];
        return (object)[
            'instagram' => $links['instagram'] ?? null,
            'linkedin'  => $links['linkedin'] ?? null,
            'website'   => $links['website'] ?? null,
            'twitter'   => $links['twitter'] ?? null,
        ];
    }
}

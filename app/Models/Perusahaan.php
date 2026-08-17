<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perusahaan extends Model
{
    use HasFactory;
    protected $table = 'perusahaans';
    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'jenis_perusahaan',
        'website_perusahaan',
        'telepon_perusahaan',
        'whatsapp',
        'legalitas',
        'deskripsi',
        'visi',
        'misi',
        'img_profile',
        // Field berikut SENGAJA TIDAK dimasukkan karena dikelola sistem:
        // 'slug'                → di-generate otomatis oleh boot() method
        // 'koin_perusahaan'     → dikelola oleh Finance melalui transaksi koin
        // 'is_berlangganan'     → dikelola oleh SuperAdmin/Finance
        // 'tanggal_berlangganan'→ dikelola oleh SuperAdmin/Finance
        // 'tanggal_expired'     → dikelola oleh sistem langganan
        // 'verification_status' → dikelola oleh Admin verifikasi
        // 'verified_at'         → diisi otomatis saat verifikasi
        // 'verification_note'   → diisi oleh Admin
    ];

    protected $casts = [
        // 'tanggal_berlangganan' => 'datetime',
        'tanggal_expired' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = static::generateUniqueSlug($model->nama_perusahaan);
        });

        static::updating(function ($model) {
            if ($model->isDirty('nama_perusahaan')) {
                $model->slug = static::generateUniqueSlug($model->nama_perusahaan);
            }
        });
    }

    protected static function generateUniqueSlug($nama)
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        // Cek apakah slug sudah ada
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (request()->routeIs('superadmin.panggilan.list')) {
            return $this->findOrFail($value);
        }


        // Hanya redirect pada GET
        if (request()->method() === 'GET' && is_numeric($value)) {

            $item = $this->findOrFail($value);

            return abort(301, '', [
                'Location' => route(
                    request()->route()->getName(),
                    [
                        'perusahaan' => $item->slug,
                    ]
                )
            ]);
        }

        // PUT / POST / DELETE → jangan redirect
        return $this->where('slug', $value)->orWhere('id', $value)->firstOrFail();
    }

    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }





    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alamat_perusahaan()
    {
        return $this->hasMany(AlamatPerusahaan::class, 'perusahaan_id');
    }

    public function pasanglowongan()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'perusahaan_id');
    }

    public function lowonganPerusahaans()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'perusahaan_id');
    }


    public function pembelianKandidats()
    {
        return $this->hasManyThrough(
            PembeliKandidat::class,
            LowonganPerusahaan::class,
            'perusahaan_id',            // FK di lowongan_perusahaans
            'lowongan_perusahaan_id',   // FK di pembeli_kandidats
            'id',                       // PK di perusahaans
            'id'                        // PK di lowongan_perusahaans
        );
    }

    public function catatanKoins()
    {
        return $this->hasMany(CatatanKoin::class, 'user_id', 'user_id');
    }

    public function talentHunters()
    {
        return $this->hasMany(TalentHunter::class, 'perusahaan_id');
    }

    public function alamatUtama()
    {
        return $this->hasOne(AlamatPerusahaan::class)->where('utama', 1);
    }
}

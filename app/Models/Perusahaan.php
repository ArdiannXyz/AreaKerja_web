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
        'slug',
        'jenis_perusahaan',
        'website_perusahaan',
        'telepon_perusahaan',
        'whatsapp',
        'legalitas',
        'deskripsi',
        'visi',
        'misi',
        'alamat',
        'kota',
        'provinsi',
        'img_profile',
        'koin_perusahaan',
        'verification_status',
        'verified_at',
        'is_berlangganan',
        'tanggal_expired',
    ];

    protected $casts = [
        'tanggal_expired' => 'datetime',
        'verified_at'     => 'datetime',
        'koin_perusahaan' => 'integer',
        'is_berlangganan' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->nama_perusahaan);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('nama_perusahaan') && empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model->nama_perusahaan);
            }
        });
    }

    protected static function generateUniqueSlug($nama)
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    public function getAlamatUtamaAttribute()
    {
        return (object)[
            'desa'      => $this->alamat,
            'detail'    => $this->alamat,
            'kode_pos'  => '60111',
            'kota'      => (object)['nama' => $this->kota ?? 'Surabaya'],
            'provinsi'  => (object)['nama' => $this->provinsi ?? 'Jawa Timur'],
        ];
    }

    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pasanglowongan()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'perusahaan_id', 'id');
    }

    public function lowonganPerusahaans()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'perusahaan_id', 'id');
    }

    public function catatanKoins()
    {
        return $this->hasMany(CatatanKoin::class, 'user_id', 'user_id');
    }

    public function talentHunters()
    {
        return $this->hasMany(TalentHunter::class, 'perusahaan_id', 'id');
    }
}

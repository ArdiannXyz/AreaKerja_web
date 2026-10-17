<?php

namespace App\Models;

use App\Models\SiteSetting;
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

    public function alamatPerusahaan()
    {
        return $this->hasMany(AlamatPerusahaan::class, 'perusahaan_id', 'id');
    }

    public function getAlamatUtamaAttribute()
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('alamat_perusahaan')) {
            $utama = $this->alamatPerusahaan()->where('utama', 1)->first();
            if ($utama) {
                $detailText = $utama->detail ?: ($utama->desa ?: $this->alamat);
                return (object)[
                    'alamat_lengkap' => $detailText,
                    'desa'           => $utama->desa ?? $detailText,
                    'detail'         => $detailText,
                    'kode_pos'       => $utama->kode_pos ?? '60111',
                    'kota'           => (object)['nama' => $utama->kota ?? $this->kota ?? 'Surabaya'],
                    'provinsi'       => (object)['nama' => $utama->provinsi ?? $this->provinsi ?? 'Jawa Timur'],
                    'kecamatan'      => (object)['nama' => $utama->kecamatan ?? '-'],
                ];
            }
        }

        if (!empty($this->alamat)) {
            return (object)[
                'alamat_lengkap' => $this->alamat,
                'desa'           => $this->alamat,
                'detail'         => $this->alamat,
                'kode_pos'       => '60111',
                'kota'           => (object)['nama' => $this->kota ?? 'Surabaya'],
                'provinsi'       => (object)['nama' => $this->provinsi ?? 'Jawa Timur'],
                'kecamatan'      => (object)['nama' => '-'],
            ];
        }

        return (object)[
            'alamat_lengkap' => null,
            'desa'           => null,
            'detail'         => null,
            'kode_pos'       => null,
            'kota'           => (object)['nama' => $this->kota ?? '-'],
            'provinsi'       => (object)['nama' => $this->provinsi ?? '-'],
            'kecamatan'      => (object)['nama' => '-'],
        ];
    }

    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    public function isVip(): bool
    {
        return (int) $this->is_berlangganan === 1 
            && !is_null($this->tanggal_expired) 
            && \Carbon\Carbon::now()->lt($this->tanggal_expired);
    }

    /**
     * Daftar opsi paket berlangganan VIP (1, 3, 6, 12 bulan)
     */
    public static function getPaketLanggananList(): array
    {
        return [
            '1_bulan' => [
                'id'           => '1_bulan',
                'durasi_bulan' => 1,
                'nama'         => '1 Bulan',
                'judul'        => 'VIP 1 Bulan',
                'badge'        => 'Starter VIP',
                'badge_color'  => 'bg-slate-100 text-slate-700 border-slate-200',
                'harga'        => (int) SiteSetting::get('harga_koin_berlangganan_1_bulan', 150),
                'deskripsi'    => 'Uji coba penuh fitur VIP untuk kebutuhan rekrutmen cepat atau jangka pendek.',
                'popular'      => false,
            ],
            '3_bulan' => [
                'id'           => '3_bulan',
                'durasi_bulan' => 3,
                'nama'         => '3 Bulan',
                'judul'        => 'VIP 3 Bulan',
                'badge'        => 'Kuartalan',
                'badge_color'  => 'bg-blue-50 text-[#00509d] border-blue-200',
                'harga'        => (int) SiteSetting::get('harga_koin_berlangganan_3_bulan', 350),
                'deskripsi'    => 'Pilihan ideal untuk siklus perekrutan kuartalan dengan tarif koin lebih hemat.',
                'popular'      => false,
            ],
            '6_bulan' => [
                'id'           => '6_bulan',
                'durasi_bulan' => 6,
                'nama'         => '6 Bulan',
                'judul'        => 'VIP 6 Bulan',
                'badge'        => 'Semi-Annual',
                'badge_color'  => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'harga'        => (int) SiteSetting::get('harga_koin_berlangganan_6_bulan', 600),
                'deskripsi'    => 'Solusi rekrutmen konsisten untuk perusahaan yang sedang aktif bertumbuh.',
                'popular'      => false,
            ],
            '12_bulan' => [
                'id'           => '12_bulan',
                'durasi_bulan' => 12,
                'nama'         => '12 Bulan (1 Tahun)',
                'judul'        => 'VIP 12 Bulan (1 Tahun)',
                'badge'        => 'Paling Hemat',
                'badge_color'  => 'bg-amber-100 text-amber-800 border-amber-300',
                'harga'        => (int) SiteSetting::get('harga_koin_berlangganan_12_bulan', SiteSetting::get('harga_koin_berlangganan', 1000)),
                'deskripsi'    => 'Akses VIP tanpa batas selama setahun penuh dengan penghematan koin maksimal.',
                'popular'      => true,
            ],
        ];
    }

    /**
     * Dapatkan detail paket berlangganan berdasarkan key/durasi
     */
    public static function getPaketLangganan(?string $key = null): ?array
    {
        $list = self::getPaketLanggananList();
        if (!$key) {
            return $list['12_bulan'] ?? reset($list);
        }

        if (isset($list[$key])) {
            return $list[$key];
        }

        if (isset($list[$key . '_bulan'])) {
            return $list[$key . '_bulan'];
        }

        return null;
    }

    public function maxActiveLowongan(): int
    {
        return $this->isVip() ? 20 : 2;
    }

    public function activeLowonganCount(): int
    {
        return $this->pasanglowongan()
            ->publik()
            ->where('status', '!=', 'tutup')
            ->where(function ($q) {
                $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
            })
            ->count();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** Relasi utama ke lowongan perusahaan (canonical) */
    public function pasanglowongan()
    {
        return $this->hasMany(LowonganPerusahaan::class, 'perusahaan_id', 'id');
    }

    /** Alias untuk backward compatibility — gunakan pasanglowongan() */
    public function lowonganPerusahaans()
    {
        return $this->pasanglowongan();
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

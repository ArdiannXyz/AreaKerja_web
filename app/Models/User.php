<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'telepon',
        'avatar',
        'password',
        'role',
        'verified',
        'status',
        'alasan_freeze_akun',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified'          => 'boolean',
        'status'            => 'integer',
    ];

    public function pelamar()
    {
        return $this->hasOne(Pelamar::class, 'user_id', 'id');
    }

    public function perusahaan()
    {
        return $this->hasOne(Perusahaan::class, 'user_id', 'id');
    }

    public function catatanKoins()
    {
        return $this->hasMany(CatatanKoin::class, 'user_id', 'id');
    }

    public function catatanCashs()
    {
        return $this->hasMany(CatatanCash::class, 'user_id', 'id');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'id');
    }

    public function getFinanceAttribute()
    {
        return (object)[
            'id'           => $this->id,
            'img_profile'  => $this->avatar ?? null,
            'nama'         => $this->nama_lengkap ?? $this->username,
            'nama_lengkap' => $this->nama_lengkap ?? $this->username,
            'email'        => $this->email,
            'telepon'      => $this->telepon ?? '',
        ];
    }

    public function getAdminAttribute()
    {
        $provinsi = null;
        $kota = null;
        $kecamatan = null;

        if ($this->provinsi_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('provinsis')) {
                    $provinsi = \Illuminate\Support\Facades\DB::table('provinsis')->where('id', $this->provinsi_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$provinsi && file_exists(database_path('data/provinces.json'))) {
                $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
                $found = collect($json)->firstWhere('id', (string)$this->provinsi_id);
                if ($found) {
                    $provinsi = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        if ($this->kota_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kotas')) {
                    $kota = \Illuminate\Support\Facades\DB::table('kotas')->where('id', $this->kota_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$kota && file_exists(database_path('data/regencies.json'))) {
                $json = json_decode(file_get_contents(database_path('data/regencies.json')), true);
                $found = collect($json)->firstWhere('id', (string)$this->kota_id);
                if ($found) {
                    $kota = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        if ($this->kecamatan_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kecamatans')) {
                    $kecamatan = \Illuminate\Support\Facades\DB::table('kecamatans')->where('id', $this->kecamatan_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$kecamatan && file_exists(database_path('data/districts.json'))) {
                $json = json_decode(file_get_contents(database_path('data/districts.json')), true);
                $found = collect($json)->firstWhere('id', (string)$this->kecamatan_id);
                if ($found) {
                    $kecamatan = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        return (object)[
            'id'           => $this->id,
            'img_profile'  => $this->avatar ?? null,
            'nama'         => $this->nama_lengkap ?? $this->username,
            'nama_lengkap' => $this->nama_lengkap ?? $this->username,
            'email'        => $this->email,
            'telepon'      => $this->telepon ?? '',
            'provinsi_id'  => $this->provinsi_id ?? null,
            'kota_id'      => $this->kota_id ?? null,
            'kecamatan_id' => $this->kecamatan_id ?? null,
            'provinsi'     => $provinsi,
            'kota'         => $kota,
            'kecamatan'    => $kecamatan,
            'desa'         => $this->desa ?? null,
            'kode_pos'     => $this->kode_pos ?? null,
            'detail'       => $this->detail_alamat ?? null,
            'detail_alamat'=> $this->detail_alamat ?? null,
        ];
    }

    public function getSuperadminAttribute()
    {
        return (object)[
            'id'           => $this->id,
            'img_profile'  => $this->avatar ?? null,
            'nama'         => $this->nama_lengkap ?? $this->username,
            'nama_lengkap' => $this->nama_lengkap ?? $this->username,
            'email'        => $this->email,
            'telepon'      => $this->telepon ?? '',
        ];
    }
}

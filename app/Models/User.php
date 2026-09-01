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
        'fcm_token',
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
        $adminRecord = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                $adminRecord = \Illuminate\Support\Facades\DB::table('admins')->where('user_id', $this->id)->first();
            }
        } catch (\Throwable $e) {}

        $provinsi_id = $adminRecord->provinsi_id ?? null;
        $kota_id = $adminRecord->kota_id ?? null;
        $kecamatan_id = $adminRecord->kecamatan_id ?? null;
        $desa = $adminRecord->desa ?? null;
        $kode_pos = $adminRecord->kode_pos ?? null;
        $detail_alamat = $adminRecord->detail_alamat ?? null;
        $img_profile = $adminRecord->img_profile ?? $this->avatar ?? null;

        $provinsi = null;
        $kota = null;
        $kecamatan = null;

        if ($provinsi_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('provinsis')) {
                    $provinsi = \Illuminate\Support\Facades\DB::table('provinsis')->where('id', $provinsi_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$provinsi && file_exists(database_path('data/provinces.json'))) {
                $json = json_decode(file_get_contents(database_path('data/provinces.json')), true);
                $found = collect($json)->firstWhere('id', (string)$provinsi_id);
                if ($found) {
                    $provinsi = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        if ($kota_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kotas')) {
                    $kota = \Illuminate\Support\Facades\DB::table('kotas')->where('id', $kota_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$kota && file_exists(database_path('data/regencies.json'))) {
                $json = json_decode(file_get_contents(database_path('data/regencies.json')), true);
                $found = collect($json)->firstWhere('id', (string)$kota_id);
                if ($found) {
                    $kota = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        if ($kecamatan_id) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('kecamatans')) {
                    $kecamatan = \Illuminate\Support\Facades\DB::table('kecamatans')->where('id', $kecamatan_id)->first();
                }
            } catch (\Throwable $e) {}

            if (!$kecamatan && file_exists(database_path('data/districts.json'))) {
                $json = json_decode(file_get_contents(database_path('data/districts.json')), true);
                $found = collect($json)->firstWhere('id', (string)$kecamatan_id);
                if ($found) {
                    $kecamatan = (object)['id' => $found['id'], 'nama' => ucwords(strtolower($found['name']))];
                }
            }
        }

        return (object)[
            'id'           => $this->id,
            'img_profile'  => $img_profile,
            'nama'         => $this->nama_lengkap ?? $this->username,
            'nama_lengkap' => $this->nama_lengkap ?? $this->username,
            'email'        => $this->email,
            'telepon'      => $this->telepon ?? '',
            'provinsi_id'  => $provinsi_id,
            'kota_id'      => $kota_id,
            'kecamatan_id' => $kecamatan_id,
            'provinsi'     => $provinsi,
            'kota'         => $kota,
            'kecamatan'    => $kecamatan,
            'desa'         => $desa,
            'kode_pos'     => $kode_pos,
            'detail'       => $detail_alamat,
            'detail_alamat'=> $detail_alamat,
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

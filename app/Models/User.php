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
        return (object)[
            'id'           => $this->id,
            'img_profile'  => $this->avatar ?? null,
            'nama'         => $this->nama_lengkap ?? $this->username,
            'nama_lengkap' => $this->nama_lengkap ?? $this->username,
            'email'        => $this->email,
            'telepon'      => $this->telepon ?? '',
            'provinsi_id'  => 1,
            'kota_id'      => 1,
            'kecamatan_id' => 1,
            'provinsi'     => (object)['id' => 1, 'nama' => 'Jawa Timur'],
            'kota'         => (object)['id' => 1, 'nama' => 'Surabaya'],
            'kecamatan'    => (object)['id' => 1, 'nama' => 'Gubeng'],
            'desa'         => 'Gubeng',
            'kode_pos'     => '60111',
            'detail'       => 'Jl. Area Kerja No. 1',
            'detail_alamat'=> 'Jl. Area Kerja No. 1',
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

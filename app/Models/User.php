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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    use HasFactory;

    protected $table = 'superadmins';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'img_profile',
        'provinsi',
        'kota',
        'kecamatan',
        'desa',
        'kode_pos',
        'detail_alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

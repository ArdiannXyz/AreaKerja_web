<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentHunter extends Model
{
    use HasFactory;
    protected $table = 'talent_hunters';
    protected $fillable = [
        'perusahaan_id',
        'alamat',
        'posisi',
        'pengalaman_kerja',
        'gender',
        'gaji_awal',
        'gaji_akhir',
        'deskripsi',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }
}

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
        'gaji_ditawarkan',
        'gaji_awal',
        'gaji_akhir',
        'deskripsi',
    ];

    protected $casts = [
        'gaji_ditawarkan' => 'integer',
    ];

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    /**
     * Accessor for displaying offered salary with fallback.
     */
    public function getGajiTampilAttribute(): string
    {
        if ($this->gaji_ditawarkan) {
            return 'Rp ' . number_format($this->gaji_ditawarkan, 0, ',', '.');
        }

        if ($this->gaji_awal || $this->gaji_akhir) {
            $awal = $this->gaji_awal ? 'Rp ' . number_format((int)$this->gaji_awal, 0, ',', '.') : '-';
            $akhir = $this->gaji_akhir ? 'Rp ' . number_format((int)$this->gaji_akhir, 0, ',', '.') : '-';
            return $awal . ' - ' . $akhir;
        }

        return 'Rp 3.500.000';
    }
}

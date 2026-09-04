<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $table = 'kotas';

    protected $fillable = [
        'provinsi_id',
        'nama',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kota_id', 'id');
    }
}

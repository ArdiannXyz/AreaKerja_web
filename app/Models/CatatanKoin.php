<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanKoin extends Model
{
    use HasFactory;
    protected $table = 'catatan_koins';
    protected $fillable = [
        'user_id',
        'no_referensi',
        'pesanan',
        'dari',
        'sumber_dana',
        'total',
    ];


    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembelikandidat()
    {
        return $this->belongsTo(PembeliKandidat::class, 'no_referensi', 'no_referensi');
    }
}

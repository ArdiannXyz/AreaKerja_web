<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarBank extends Model
{
    use HasFactory;
    protected $table = 'daftar_bank';
    protected $guarded = [];

    public function catatanCash()
    {
        return $this->hasMany(CatatanCash::class, 'daftar_bank_id');
    }
}

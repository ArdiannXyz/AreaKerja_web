<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verifications';

    protected $fillable = [
        'user_id',
        'new_email',
        'token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

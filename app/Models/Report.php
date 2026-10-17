<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public const TIPE_LOWONGAN = 'lowongan';
    public const TIPE_PELAMAR = 'pelamar';

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISMISSED = 'dismissed';
    public const STATUS_RESOLVED = 'resolved';

    public const ACTION_NONE = 'none';
    public const ACTION_HIDE = 'hide_lowongan';
    public const ACTION_WARN = 'warn';
    public const ACTION_FREEZE = 'freeze_user';
    public const ACTION_RESTORE = 'restore';
    public const ACTION_REVOKE_KANDIDAT = 'revoke_kandidat';

    public const ALASAN_LOWONGAN = [
        'scam'          => 'Penipuan / lowongan palsu',
        'menyesatkan'   => 'Informasi menyesatkan',
        'diskriminasi'  => 'Diskriminasi',
        'duplikat'      => 'Lowongan duplikat',
        'tidak_pantas'  => 'Konten tidak pantas',
        'lainnya'       => 'Lainnya',
    ];

    public const ALASAN_PELAMAR = [
        'pemalsuan_dokumen'   => 'Pemalsuan dokumen / identitas / CV',
        'ghosting'            => 'Tidak hadir interview tanpa kabar (Ghosting)',
        'pelanggaran_kontrak' => 'Pelanggaran kontrak / indisipliner / PHK bermasalah',
        'perilaku_buruk'      => 'Perilaku tidak pantas / pelecehan / ancaman',
        'lainnya'             => 'Lainnya',
    ];

    public const ALASAN = [
        'scam'                => 'Penipuan / lowongan palsu',
        'menyesatkan'         => 'Informasi menyesatkan',
        'diskriminasi'        => 'Diskriminasi',
        'duplikat'            => 'Lowongan duplikat',
        'tidak_pantas'        => 'Konten tidak pantas',
        'pemalsuan_dokumen'   => 'Pemalsuan dokumen / identitas / CV',
        'ghosting'            => 'Tidak hadir interview tanpa kabar (Ghosting)',
        'pelanggaran_kontrak' => 'Pelanggaran kontrak / indisipliner / PHK bermasalah',
        'perilaku_buruk'      => 'Perilaku tidak pantas / pelecehan / ancaman',
        'lainnya'             => 'Lainnya',
    ];

    protected $fillable = [
        'tipe',
        'reporter_user_id',
        'lowongan_id',
        'pelamar_id',
        'reported_user_id',
        'alasan',
        'deskripsi',
        'bukti_path',
        'status',
        'action_taken',
        'admin_id',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(LowonganPerusahaan::class, 'lowongan_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeTipeLowongan($query)
    {
        return $query->where('tipe', self::TIPE_LOWONGAN);
    }

    public function scopeTipePelamar($query)
    {
        return $query->where('tipe', self::TIPE_PELAMAR);
    }

    public function isPelamar(): bool
    {
        return $this->tipe === self::TIPE_PELAMAR;
    }

    public function isLowongan(): bool
    {
        return $this->tipe === self::TIPE_LOWONGAN || empty($this->tipe);
    }

    public function getAlasanLabelAttribute(): string
    {
        return self::ALASAN[$this->alasan] ?? $this->alasan;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'Menunggu tinjauan',
            self::STATUS_DISMISSED => 'Ditolak',
            self::STATUS_RESOLVED  => 'Selesai',
            default                => $this->status,
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action_taken) {
            self::ACTION_HIDE            => 'Lowongan disembunyikan',
            self::ACTION_WARN            => $this->isPelamar() ? 'Peringatan dikirim ke pelamar' : 'Peringatan dikirim',
            self::ACTION_FREEZE          => $this->isPelamar() ? 'Akun pelamar dibekukan' : 'Akun perusahaan dibekukan',
            self::ACTION_REVOKE_KANDIDAT => 'Status kandidat aktif dicabut',
            self::ACTION_RESTORE         => 'Lowongan dipulihkan',
            self::ACTION_NONE            => 'Tidak ada sanksi',
            default                      => '-',
        };
    }
}

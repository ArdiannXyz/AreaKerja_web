<?php

namespace App\Services;

use App\Models\LowonganPerusahaan;
use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportService
{
    public const DAILY_LIMIT = 5;

    /**
     * Pelamar melaporkan Lowongan Perusahaan
     */
    public function store(User $reporter, LowonganPerusahaan $lowongan, array $data, ?UploadedFile $bukti = null): Report
    {
        if ($reporter->role !== 'pelamar') {
            throw ValidationException::withMessages([
                'lowongan_id' => 'Hanya pelamar yang dapat melaporkan lowongan.',
            ]);
        }

        if ($lowongan->isDisembunyikan()) {
            throw ValidationException::withMessages([
                'lowongan_id' => 'Lowongan ini sudah tidak ditayangkan.',
            ]);
        }

        if ($lowongan->perusahaan && $lowongan->perusahaan->user_id === $reporter->id) {
            throw ValidationException::withMessages([
                'lowongan_id' => 'Anda tidak dapat melaporkan lowongan milik sendiri.',
            ]);
        }

        $alreadyPending = Report::where('reporter_user_id', $reporter->id)
            ->where('lowongan_id', $lowongan->id)
            ->pending()
            ->exists();

        if ($alreadyPending) {
            throw ValidationException::withMessages([
                'lowongan_id' => 'Anda sudah memiliki laporan yang sedang ditinjau untuk lowongan ini.',
            ]);
        }

        $todayCount = Report::where('reporter_user_id', $reporter->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayCount >= self::DAILY_LIMIT) {
            throw ValidationException::withMessages([
                'alasan' => 'Batas laporan harian telah tercapai. Coba lagi besok.',
            ]);
        }

        $buktiPath = null;
        if ($bukti) {
            $buktiPath = $bukti->store('reports/lowongan', 'public');
        }

        return Report::create([
            'tipe'             => Report::TIPE_LOWONGAN,
            'reporter_user_id' => $reporter->id,
            'lowongan_id'      => $lowongan->id,
            'reported_user_id' => $lowongan->perusahaan?->user_id,
            'alasan'           => $data['alasan'],
            'deskripsi'        => $data['deskripsi'],
            'bukti_path'       => $buktiPath,
            'status'           => Report::STATUS_PENDING,
        ]);
    }

    /**
     * Perusahaan melaporkan Pelamar Problematik
     */
    public function storePelamarReport(User $reporter, Pelamar $pelamar, array $data, ?UploadedFile $bukti = null, ?LowonganPerusahaan $lowongan = null): Report
    {
        if ($reporter->role !== 'perusahaan') {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Hanya perusahaan yang dapat melaporkan pelamar.',
            ]);
        }

        if ($pelamar->user_id === $reporter->id) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Anda tidak dapat melaporkan akun sendiri.',
            ]);
        }

        $alreadyPending = Report::where('reporter_user_id', $reporter->id)
            ->where('pelamar_id', $pelamar->id)
            ->pending()
            ->exists();

        if ($alreadyPending) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Anda sudah memiliki laporan yang sedang ditinjau untuk pelamar ini.',
            ]);
        }

        $todayCount = Report::where('reporter_user_id', $reporter->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayCount >= self::DAILY_LIMIT) {
            throw ValidationException::withMessages([
                'alasan' => 'Batas laporan harian telah tercapai. Coba lagi besok.',
            ]);
        }

        $buktiPath = null;
        if ($bukti) {
            $buktiPath = $bukti->store('reports/pelamar', 'public');
        }

        return Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $reporter->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'lowongan_id'      => $lowongan?->id,
            'alasan'           => $data['alasan'],
            'deskripsi'        => $data['deskripsi'],
            'bukti_path'       => $buktiPath,
            'status'           => Report::STATUS_PENDING,
        ]);
    }

    /**
     * Admin melaporkan kandidat (kandidat/calon kandidat) melalui panel admin
     */
    public function storeKandidatReport(User $reporter, Pelamar $pelamar, array $data, ?UploadedFile $bukti = null): Report
    {
        if (!in_array($reporter->role, ['admin', 'super_admin'])) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Hanya admin atau super admin yang dapat melaporkan kandidat.',
            ]);
        }

        if ($pelamar->user_id === $reporter->id) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Anda tidak dapat melaporkan akun sendiri.',
            ]);
        }

        $alreadyPending = Report::where('reporter_user_id', $reporter->id)
            ->where('pelamar_id', $pelamar->id)
            ->pending()
            ->exists();

        if ($alreadyPending) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Anda sudah memiliki laporan yang sedang ditinjau untuk kandidat ini.',
            ]);
        }

        $todayCount = Report::where('reporter_user_id', $reporter->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayCount >= self::DAILY_LIMIT) {
            throw ValidationException::withMessages([
                'alasan' => 'Batas laporan harian telah tercapai. Coba lagi besok.',
            ]);
        }

        $buktiPath = null;
        if ($bukti) {
            $buktiPath = $bukti->store('reports/kandidat', 'public');
        }

        return Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $reporter->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => $data['alasan'],
            'deskripsi'        => $data['deskripsi'],
            'bukti_path'       => $buktiPath,
            'status'           => Report::STATUS_PENDING,
        ]);
    }

    /**
     * Review laporan oleh Admin / Super Admin
     */
    public function review(Report $report, User $admin, string $action, ?string $notes = null, ?string $alasanFreeze = null): Report
    {
        return DB::transaction(function () use ($report, $admin, $action, $notes, $alasanFreeze) {
            if ($report->isPelamar()) {
                return $this->reviewPelamarReport($report, $admin, $action, $notes, $alasanFreeze);
            }

            return $this->reviewLowonganReport($report, $admin, $action, $notes, $alasanFreeze);
        });
    }

    protected function reviewLowonganReport(Report $report, User $admin, string $action, ?string $notes = null, ?string $alasanFreeze = null): Report
    {
        $report->load('lowongan.perusahaan.user');
        $lowongan = $report->lowongan;

        if (! $lowongan) {
            throw ValidationException::withMessages([
                'lowongan_id' => 'Lowongan tidak ditemukan.',
            ]);
        }

        match ($action) {
            'dismiss' => $this->markReports($lowongan, $admin, Report::STATUS_DISMISSED, Report::ACTION_NONE, $notes, $report),
            'hide'    => $this->hideLowongan($lowongan, $admin, $notes, $report),
            'warn'    => $this->warnCompany($lowongan, $admin, $notes, $report),
            'freeze'  => $this->freezeCompany($lowongan, $admin, $notes, $alasanFreeze, $report),
            'restore' => $this->restoreLowongan($lowongan, $admin, $notes, $report),
            default   => throw ValidationException::withMessages(['action' => 'Aksi tidak dikenali untuk laporan lowongan.']),
        };

        return $report->fresh(['lowongan.perusahaan.user', 'reporter', 'admin']);
    }

    protected function reviewPelamarReport(Report $report, User $admin, string $action, ?string $notes = null, ?string $alasanFreeze = null): Report
    {
        $report->load(['pelamar.user', 'reporter.perusahaan']);
        $pelamar = $report->pelamar;

        if (! $pelamar) {
            throw ValidationException::withMessages([
                'pelamar_id' => 'Pelamar tidak ditemukan.',
            ]);
        }

        match ($action) {
            'dismiss'         => $this->markPelamarReports($pelamar->id, $admin, Report::STATUS_DISMISSED, Report::ACTION_NONE, $notes, $report),
            'warn'            => $this->warnPelamar($pelamar, $admin, $notes, $report),
            'revoke_kandidat' => $this->revokeKandidatPelamar($pelamar, $admin, $notes, $report),
            'freeze'          => $this->freezePelamar($pelamar, $admin, $notes, $alasanFreeze, $report),
            'unfreeze'        => $this->unfreezePelamar($pelamar, $admin, $notes, $report),
            default           => throw ValidationException::withMessages(['action' => 'Aksi tidak dikenali untuk laporan pelamar.']),
        };

        return $report->fresh(['pelamar.user', 'reporter.perusahaan', 'admin']);
    }

    protected function warnPelamar(Pelamar $pelamar, User $admin, ?string $notes, Report $current): void
    {
        $this->markPelamarReports($pelamar->id, $admin, Report::STATUS_RESOLVED, Report::ACTION_WARN, $notes, $current);

        if ($pelamar->user_id) {
            $this->notifyUser(
                $pelamar->user_id,
                'Peringatan Moderasi Akun',
                'Terdapat laporan resmi dari perusahaan terkait pelanggaran/ketidaksesuaian data atau komitmen kerja Anda. Mohon jaga integritas dan etika kerja Anda di AreaKerja.'
            );
        }
    }

    protected function revokeKandidatPelamar(Pelamar $pelamar, User $admin, ?string $notes, Report $current): void
    {
        $pelamar->update([
            'kategori' => 'kandidat nonaktif',
        ]);

        $this->markPelamarReports($pelamar->id, $admin, Report::STATUS_RESOLVED, Report::ACTION_REVOKE_KANDIDAT, $notes, $current);

        if ($pelamar->user_id) {
            $this->notifyUser(
                $pelamar->user_id,
                'Status Kandidat Aktif Dicabut',
                'Status Kandidat Aktif Anda telah dinonaktifkan oleh tim moderasi AreaKerja setelah meninjau laporan pelanggaran yang masuk.'
            );
        }
    }

    protected function freezePelamar(Pelamar $pelamar, User $admin, ?string $notes, ?string $alasanFreeze, Report $current): void
    {
        $user = $pelamar->user;
        if (! $user) {
            throw ValidationException::withMessages([
                'action' => 'Akun user pelamar tidak ditemukan.',
            ]);
        }

        if ($user->id === $admin->id) {
            throw ValidationException::withMessages([
                'action' => 'Anda tidak dapat membekukan akun sendiri.',
            ]);
        }

        $user->update([
            'status'             => 1,
            'alasan_freeze_akun' => $alasanFreeze ?: ($notes ?: 'Dibekukan dari moderasi laporan perusahaan'),
        ]);

        $this->markPelamarReports($pelamar->id, $admin, Report::STATUS_RESOLVED, Report::ACTION_FREEZE, $notes, $current);

        $this->notifyUser(
            $user->id,
            'Akun Anda Dibekukan',
            'Akun Anda telah dibekukan oleh tim moderasi AreaKerja karena adanya laporan pelanggaran yang telah diverifikasi.'
        );
    }

    protected function unfreezePelamar(Pelamar $pelamar, User $admin, ?string $notes, Report $current): void
    {
        $user = $pelamar->user;
        if ($user) {
            $user->update([
                'status'             => 0,
                'alasan_freeze_akun' => null,
            ]);
        }

        $this->markPelamarReports($pelamar->id, $admin, Report::STATUS_RESOLVED, Report::ACTION_RESTORE, $notes, $current);

        if ($user) {
            $this->notifyUser(
                $user->id,
                'Akun Anda Dipulihkan',
                'Pembekuan akun Anda telah dicabut oleh tim moderasi AreaKerja.'
            );
        }
    }

    protected function markPelamarReports(int $pelamarId, User $admin, string $status, string $action, ?string $notes, Report $current): void
    {
        Report::where('pelamar_id', $pelamarId)
            ->where(function ($query) use ($current) {
                $query->pending()->orWhere('id', $current->id);
            })
            ->update([
                'status'       => $status,
                'action_taken' => $action,
                'admin_id'     => $admin->id,
                'admin_notes'  => $notes,
                'resolved_at'  => now(),
            ]);
    }

    protected function hideLowongan(LowonganPerusahaan $lowongan, User $admin, ?string $notes, Report $current): void
    {
        if (! $lowongan->isDisembunyikan()) {
            $lowongan->status_sebelum_moderasi = $lowongan->status ?: 'buka';
            $lowongan->status = LowonganPerusahaan::STATUS_DISEMBUNYIKAN;
            $lowongan->save();
        }

        $this->markReports($lowongan, $admin, Report::STATUS_RESOLVED, Report::ACTION_HIDE, $notes, $current);
        $this->notifyCompany(
            $lowongan,
            'Lowongan ditinjau oleh moderator',
            "Lowongan \"{$lowongan->nama}\" disembunyikan dari listing karena terbukti melanggar ketentuan. Hubungi bantuan jika ingin klarifikasi."
        );
    }

    protected function restoreLowongan(LowonganPerusahaan $lowongan, User $admin, ?string $notes, Report $current): void
    {
        if ($lowongan->isDisembunyikan()) {
            $lowongan->status = $lowongan->status_sebelum_moderasi ?: 'buka';
            $lowongan->status_sebelum_moderasi = null;
            $lowongan->save();
        }

        $this->markReports($lowongan, $admin, Report::STATUS_RESOLVED, Report::ACTION_RESTORE, $notes, $current);
        $this->notifyCompany(
            $lowongan,
            'Lowongan dipulihkan',
            "Lowongan \"{$lowongan->nama}\" sudah ditayangkan kembali di AreaKerja."
        );
    }

    protected function warnCompany(LowonganPerusahaan $lowongan, User $admin, ?string $notes, Report $current): void
    {
        $this->markReports($lowongan, $admin, Report::STATUS_RESOLVED, Report::ACTION_WARN, $notes, $current);
        $this->notifyCompany(
            $lowongan,
            'Peringatan moderasi',
            "Ada laporan terhadap lowongan \"{$lowongan->nama}\". Mohon periksa kembali isi lowongan agar sesuai ketentuan AreaKerja."
        );
    }

    protected function freezeCompany(LowonganPerusahaan $lowongan, User $admin, ?string $notes, ?string $alasanFreeze, Report $current): void
    {
        $user = $lowongan->perusahaan?->user;
        if (! $user) {
            throw ValidationException::withMessages([
                'action' => 'Akun perusahaan tidak ditemukan.',
            ]);
        }

        if ($user->id === $admin->id) {
            throw ValidationException::withMessages([
                'action' => 'Anda tidak dapat membekukan akun sendiri.',
            ]);
        }

        if (! $lowongan->isDisembunyikan()) {
            $lowongan->status_sebelum_moderasi = $lowongan->status ?: 'buka';
            $lowongan->status = LowonganPerusahaan::STATUS_DISEMBUNYIKAN;
            $lowongan->save();
        }

        $user->update([
            'status'             => 1,
            'alasan_freeze_akun' => $alasanFreeze ?: ($notes ?: 'Dibekukan dari moderasi laporan lowongan'),
        ]);

        $this->markReports($lowongan, $admin, Report::STATUS_RESOLVED, Report::ACTION_FREEZE, $notes, $current);
        $this->notifyCompany(
            $lowongan,
            'Akun dibekukan',
            'Akun perusahaan Anda dibekukan karena pelanggaran yang terkait lowongan yang dilaporkan.'
        );
    }

    protected function markReports(LowonganPerusahaan $lowongan, User $admin, string $status, string $action, ?string $notes, Report $current): void
    {
        Report::where('lowongan_id', $lowongan->id)
            ->where(function ($query) use ($current) {
                $query->pending()->orWhere('id', $current->id);
            })
            ->update([
                'status'       => $status,
                'action_taken' => $action,
                'admin_id'     => $admin->id,
                'admin_notes'  => $notes,
                'resolved_at'  => now(),
            ]);
    }

    protected function notifyCompany(LowonganPerusahaan $lowongan, string $judul, string $pesan): void
    {
        $userId = $lowongan->perusahaan?->user_id;
        if (! $userId) {
            return;
        }

        $this->notifyUser($userId, $judul, $pesan, $lowongan->perusahaan_id, $lowongan->id);
    }

    protected function notifyUser(int $userId, string $judul, string $pesan, ?int $perusahaanId = null, ?int $lowonganId = null): void
    {
        Notifikasi::create([
            'user_id'             => $userId,
            'perusahaan_id'       => $perusahaanId,
            'lowongan_id'         => $lowonganId,
            'judul'               => $judul,
            'pesan'               => $pesan,
            'is_read'             => 0,
            'expired_at'          => now()->addDays(14),
            'pelamar_lowongan_id' => null,
        ]);
    }
}

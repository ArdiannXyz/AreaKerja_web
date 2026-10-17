<?php

namespace Tests\Feature;

use App\Models\Notifikasi;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelamarReportTest extends TestCase
{
    use DatabaseTransactions;

    private function createPerusahaan(): array
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'perusahaan_' . $unique,
            'nama_lengkap' => 'PT Maju Bersama',
            'email'        => 'perusahaan_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'perusahaan',
            'status'       => 0,
            'verified'     => true,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Maju Bersama',
            'koin_perusahaan'     => 100,
            'verification_status' => 'approved',
        ]);

        return [$user, $perusahaan];
    }

    private function createPelamar(): Pelamar
    {
        $unique = uniqid();
        $user = User::create([
            'username'     => 'pelamar_' . $unique,
            'nama_lengkap' => 'Joko Anwar',
            'email'        => 'pelamar_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'pelamar',
            'status'       => 0,
            'verified'     => true,
        ]);

        return Pelamar::create([
            'user_id'       => $user->id,
            'nama_pelamar'  => 'Joko Anwar',
            'gender'        => 'Laki-laki',
            'tanggal_lahir' => '1998-05-10',
            'kategori'      => 'kandidat aktif',
            'skills'        => ['PHP', 'Laravel'],
        ]);
    }

    private function createAdmin(): User
    {
        $unique = uniqid();
        return User::create([
            'username'     => 'admin_' . $unique,
            'nama_lengkap' => 'Admin Test',
            'email'        => 'admin_' . $unique . '@test.com',
            'password'     => bcrypt('password'),
            'role'         => 'admin',
            'status'       => 0,
            'verified'     => true,
        ]);
    }

    public function test_perusahaan_can_report_pelamar(): void
    {
        Storage::fake('public');
        [$perusahaanUser] = $this->createPerusahaan();
        $pelamar = $this->createPelamar();

        $response = $this->actingAs($perusahaanUser)->post(route('perusahaan.pelamar.report', $pelamar), [
            'alasan'    => 'pelanggaran_kontrak',
            'deskripsi' => 'Pelamar di-PHK karena tidak hadir tanpa kabar dan melanggar klausul kontrak kerja.',
            'bukti'     => UploadedFile::fake()->create('surat_phk.pdf', 500, 'application/pdf'),
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $perusahaanUser->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => 'pelanggaran_kontrak',
            'status'           => Report::STATUS_PENDING,
        ]);
    }

    public function test_perusahaan_cannot_report_same_pelamar_twice_while_pending(): void
    {
        [$perusahaanUser] = $this->createPerusahaan();
        $pelamar = $this->createPelamar();

        Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $perusahaanUser->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => 'ghosting',
            'deskripsi'        => 'Laporan pertama yang masih pending.',
            'status'           => Report::STATUS_PENDING,
        ]);

        $response = $this->actingAs($perusahaanUser)->post(route('perusahaan.pelamar.report', $pelamar), [
            'alasan'    => 'pemalsuan_dokumen',
            'deskripsi' => 'Mencoba lapor lagi saat laporan sebelumnya masih pending.',
        ]);

        $response->assertSessionHasErrors('pelamar_id');
    }

    public function test_admin_can_review_and_warn_pelamar(): void
    {
        $admin = $this->createAdmin();
        [$perusahaanUser] = $this->createPerusahaan();
        $pelamar = $this->createPelamar();

        $report = Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $perusahaanUser->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => 'ghosting',
            'deskripsi'        => 'Pelamar tidak hadir interview tanpa konfirmasi.',
            'status'           => Report::STATUS_PENDING,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.reports.review', $report), [
            'action'      => 'warn',
            'admin_notes' => 'Teguran pertama untuk pelamar.',
        ]);

        $response->assertRedirect(route('admin.reports.show', $report));
        $this->assertEquals(Report::STATUS_RESOLVED, $report->fresh()->status);
        $this->assertEquals(Report::ACTION_WARN, $report->fresh()->action_taken);

        $this->assertDatabaseHas('notifikasis', [
            'user_id' => $pelamar->user_id,
            'judul'   => 'Peringatan Moderasi Akun',
        ]);
    }

    public function test_admin_can_revoke_kandidat_aktif(): void
    {
        $admin = $this->createAdmin();
        [$perusahaanUser] = $this->createPerusahaan();
        $pelamar = $this->createPelamar();

        $this->assertEquals('kandidat aktif', $pelamar->kategori);

        $report = Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $perusahaanUser->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => 'pemalsuan_dokumen',
            'deskripsi'        => 'CV tidak valid.',
            'status'           => Report::STATUS_PENDING,
        ]);

        $this->actingAs($admin)->post(route('admin.reports.review', $report), [
            'action' => 'revoke_kandidat',
        ]);

        $this->assertEquals('kandidat nonaktif', $pelamar->fresh()->kategori);
        $this->assertEquals(Report::ACTION_REVOKE_KANDIDAT, $report->fresh()->action_taken);
    }

    public function test_admin_can_freeze_pelamar_account(): void
    {
        $admin = $this->createAdmin();
        [$perusahaanUser] = $this->createPerusahaan();
        $pelamar = $this->createPelamar();

        $report = Report::create([
            'tipe'             => Report::TIPE_PELAMAR,
            'reporter_user_id' => $perusahaanUser->id,
            'pelamar_id'       => $pelamar->id,
            'reported_user_id' => $pelamar->user_id,
            'alasan'           => 'pelanggaran_kontrak',
            'deskripsi'        => 'Tindakan fraud dan di-PHK bermasalah.',
            'status'           => Report::STATUS_PENDING,
        ]);

        $this->actingAs($admin)->post(route('admin.reports.review', $report), [
            'action'        => 'freeze',
            'alasan_freeze' => 'Terbukti melakukan pelanggaran berat di tempat kerja.',
        ]);

        $this->assertEquals(1, $pelamar->user->fresh()->status);
        $this->assertEquals('Terbukti melakukan pelanggaran berat di tempat kerja.', $pelamar->user->fresh()->alasan_freeze_akun);
        $this->assertEquals(Report::ACTION_FREEZE, $report->fresh()->action_taken);
    }
}

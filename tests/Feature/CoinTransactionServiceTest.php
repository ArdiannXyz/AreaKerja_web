<?php

namespace Tests\Feature;

use App\Models\CatatanKoin;
use App\Models\Perusahaan;
use App\Models\User;
use App\Services\CoinTransactionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CoinTransactionServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CoinTransactionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CoinTransactionService::class);
    }

    /** @test */
    public function it_can_credit_and_deduct_coins_safely()
    {
        $user = User::create([
            'username'     => 'test_employer_' . time(),
            'nama_lengkap' => 'Test Employer',
            'email'        => 'employer_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'perusahaan',
            'verified'     => true,
            'status'       => 1,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Testing Maju',
            'koin_perusahaan'     => 500,
            'verification_status' => 'approved',
        ]);

        // 1. Credit 200 coins
        $creditCatatan = $this->service->credit(
            $perusahaan,
            200,
            'Top Up 200 Koin',
            'Transfer Bank',
            'TEST-TOPUP-01',
            'Finance'
        );

        $perusahaan->refresh();
        $this->assertEquals(700, $perusahaan->koin_perusahaan);
        $this->assertDatabaseHas('catatan_koins', [
            'no_referensi' => 'TEST-TOPUP-01',
            'total'        => '+200',
        ]);

        // 2. Deduct 300 coins
        $deductCatatan = $this->service->deduct(
            $perusahaan,
            300,
            'Beli Paket Lowongan',
            'Pembelian Paket',
            'TEST-BUY-01'
        );

        $perusahaan->refresh();
        $this->assertEquals(400, $perusahaan->koin_perusahaan);
        $this->assertDatabaseHas('catatan_koins', [
            'no_referensi' => 'TEST-BUY-01',
            'total'        => '-300',
        ]);

        // 3. Refund 100 coins
        $refundCatatan = $this->service->refund(
            $perusahaan,
            100,
            'Refund Iklan Ditolak',
            'Refund Iklan',
            'TEST-REFUND-01'
        );

        $perusahaan->refresh();
        $this->assertEquals(500, $perusahaan->koin_perusahaan);
        $this->assertDatabaseHas('catatan_koins', [
            'no_referensi' => 'TEST-REFUND-01',
            'total'        => '+100',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_balance_insufficient()
    {
        $this->expectException(\RuntimeException::class);

        $user = User::create([
            'username'     => 'test_broke_' . time(),
            'nama_lengkap' => 'Test Broke',
            'email'        => 'broke_' . time() . '@test.com',
            'password'     => bcrypt('secret123'),
            'role'         => 'perusahaan',
            'verified'     => true,
            'status'       => 1,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'             => $user->id,
            'nama_perusahaan'     => 'PT Saldo Habis',
            'koin_perusahaan'     => 50,
            'verification_status' => 'approved',
        ]);

        // Attempt to deduct 100 from balance of 50
        $this->service->deduct(
            $perusahaan,
            100,
            'Mencoba beli melebihi saldo',
            'Pembelian Gagal'
        );
    }
}


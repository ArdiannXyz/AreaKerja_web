<?php

namespace Tests\Feature;

use App\Models\CatatanCash;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\User;
use App\Services\CoinTransactionService;
use App\Services\XenditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class XenditPaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected string $webhookToken = 'test-webhook-token-12345';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.xendit.webhook_token', $this->webhookToken);
        Config::set('services.xendit.secret_key', 'xnd_development_dummy_key');
    }

    public function test_webhook_rejects_unauthorized_token(): void
    {
        $payload = [
            'external_id' => 'TRX-CASH-123',
            'status'      => 'PAID',
            'amount'      => 50000,
        ];

        $response = $this->postJson('/payment/xendit/callback', $payload, [
            'x-callback-token' => 'invalid-token',
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Invalid callback token.']);
    }

    public function test_webhook_processes_paid_company_topup_and_credits_coins(): void
    {
        $user = User::create([
            'username'     => 'perusahaan_test',
            'email'        => 'pt_test@gmail.com',
            'password'     => Hash::make('password'),
            'role'         => 'perusahaan',
            'verified'     => 1,
            'status'       => 0,
        ]);

        $perusahaan = Perusahaan::create([
            'user_id'            => $user->id,
            'namaPerusahaan'     => 'PT Testing Area Kerja',
            'email'              => 'pt_test@gmail.com',
            'koin_perusahaan'    => 10,
        ]);

        $trx = CatatanCash::create([
            'user_id'         => $user->id,
            'no_transaksi'    => 'TRX-CASH-' . time(),
            'pesanan'         => 'Top Up 100 Koin',
            'total'           => 100000,
            'status'          => 'pending',
            'metode_bayar'    => 'xendit',
            'xendit_id'       => 'inv_test_123',
            'checkout_url'    => 'https://checkout-staging.xendit.co/web/inv_test_123',
        ]);

        $payload = [
            'id'          => 'inv_test_123',
            'external_id' => $trx->no_transaksi,
            'status'      => 'PAID',
            'amount'      => 100000,
            'paid_at'     => now()->toIso8601String(),
        ];

        $response = $this->postJson('/payment/xendit/callback', $payload, [
            'x-callback-token' => $this->webhookToken,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $trx->refresh();
        $this->assertEquals('diterima', $trx->status);
        $this->assertNotNull($trx->paid_at);

        $perusahaan->refresh();
        $this->assertEquals(110, $perusahaan->koin_perusahaan);
    }

    public function test_webhook_processes_candidate_registration_payment(): void
    {
        $user = User::create([
            'username'     => 'kandidat_test',
            'email'        => 'kandidat@gmail.com',
            'password'     => Hash::make('password'),
            'role'         => 'pelamar',
            'verified'     => 1,
            'status'       => 0,
        ]);

        $pelamar = Pelamar::create([
            'user_id'         => $user->id,
            'nama_lengkap'    => 'Kandidat Hebat',
            'email'           => 'kandidat@gmail.com',
            'kategori'        => 'pelamar',
        ]);

        $trx = CatatanCash::create([
            'user_id'         => $user->id,
            'no_transaksi'    => 'TRX-KANDIDAT-' . time(),
            'pesanan'         => 'Pendaftaran Kandidat',
            'total'           => 200000,
            'status'          => 'pending',
            'metode_bayar'    => 'xendit',
            'xendit_id'       => 'inv_kandidat_456',
            'checkout_url'    => 'https://checkout-staging.xendit.co/web/inv_kandidat_456',
        ]);

        $payload = [
            'id'          => 'inv_kandidat_456',
            'external_id' => $trx->no_transaksi,
            'status'      => 'SETTLED',
            'amount'      => 200000,
            'paid_at'     => now()->toIso8601String(),
        ];

        $response = $this->postJson('/api/v1/xendit/callback', $payload, [
            'x-callback-token' => $this->webhookToken,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $trx->refresh();
        $this->assertEquals('diterima', $trx->status);

        $pelamar->refresh();
        $this->assertEquals('calon kandidat', $pelamar->kategori);
    }

    public function test_webhook_marks_expired_invoice(): void
    {
        $user = User::create([
            'username'     => 'user_expired',
            'email'        => 'expired@gmail.com',
            'password'     => Hash::make('password'),
            'role'         => 'pelamar',
            'verified'     => 1,
            'status'       => 0,
        ]);

        $trx = CatatanCash::create([
            'user_id'         => $user->id,
            'no_transaksi'    => 'TRX-EXP-' . time(),
            'pesanan'         => 'Top Up Koin',
            'total'           => 50000,
            'status'          => 'pending',
            'metode_bayar'    => 'xendit',
        ]);

        $payload = [
            'id'          => 'inv_exp_789',
            'external_id' => $trx->no_transaksi,
            'status'      => 'EXPIRED',
            'amount'      => 50000,
        ];

        $response = $this->postJson('/payment/xendit/callback', $payload, [
            'x-callback-token' => $this->webhookToken,
        ]);

        $response->assertStatus(200);

        $trx->refresh();
        $this->assertEquals('expired', $trx->status);
    }
}


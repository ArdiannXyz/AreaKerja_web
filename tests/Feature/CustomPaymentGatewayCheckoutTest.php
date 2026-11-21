<?php

namespace Tests\Feature;

use App\Models\CatatanCash;
use App\Models\Pelamar;
use App\Models\Perusahaan;
use App\Models\SuperAdmin;
use App\Models\User;
use App\Services\PaymentSettingService;
use App\Services\QrisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomPaymentGatewayCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Perusahaan $perusahaan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'perusahaan_checkout',
            'email' => 'checkout_pt@areakerja.com',
            'password' => Hash::make('password123'),
            'role' => 'perusahaan',
            'verified' => 1,
            'status' => 0,
        ]);

        $this->perusahaan = Perusahaan::create([
            'user_id' => $this->user->id,
            'namaPerusahaan' => 'PT Solusi Fintech Indonesia',
            'email' => 'checkout_pt@areakerja.com',
            'koin_perusahaan' => 50,
            'verification_status' => 'approved',
        ]);
    }

    public function test_qris_service_generates_valid_emvco_payload_and_svg_inline(): void
    {
        $payload = QrisService::buildPayload(
            merchantName: 'AREA KERJA INDONESIA',
            city: 'YOGYAKARTA',
            postalCode: '55281',
            nmid: 'ID1020038920192',
            amount: 100000,
            referenceNo: 'AK-TEST-001'
        );

        $this->assertStringStartsWith('000201', $payload);
        $this->assertStringContainsString('AREA KERJA', $payload);
        $this->assertStringContainsString('ID1020038920192', $payload);

        $svg = QrisService::renderSvg($payload);
        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
    }

    public function test_user_can_access_bills_page_and_see_pending_transactions(): void
    {
        PaymentSettingService::updateSettings(['active_payment_mode' => 'xendit']);

        $trx = CatatanCash::create([
            'user_id' => $this->user->id,
            'no_referensi' => 'AK-PENDING-999',
            'pesanan' => 'Top Up 100 Koin Area Kerja',
            'total' => 100000,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        $response = $this->actingAs($this->user)->get(route('tenant.bills.index'));

        $response->assertStatus(200);
        $response->assertSee('AK-PENDING-999');
        $response->assertSee('Top Up 100 Koin Area Kerja');
        $response->assertSee('Rp 100.000');
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Bayar Sekarang');
        $response->assertSee('Batalkan');
    }

    public function test_pay_now_opens_checkout_page_with_valid_qris_svg_and_channels(): void
    {
        PaymentSettingService::updateSettings(['active_payment_mode' => 'xendit']);

        $trx = CatatanCash::create([
            'user_id' => $this->user->id,
            'no_referensi' => 'AK-CHECKOUT-888',
            'pesanan' => 'Top Up 1000 Koin Area Kerja',
            'total' => 500000,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        // Verifikasi route pay_now mengarahkan ke halaman checkout
        $payNowResponse = $this->actingAs($this->user)->get(route('catatan_cash.pay_now', $trx->id));
        $payNowResponse->assertRedirect(route('catatan_cash.show', $trx->id));

        // Akses halaman checkout
        $response = $this->actingAs($this->user)->get(route('catatan_cash.show', $trx->id));

        $response->assertStatus(200);
        $response->assertSee('Pilih Metode Pembayaran');
        $response->assertSee('QRIS');
        $response->assertSee('E-Wallet');
        $response->assertSee('Virtual Account');
        $response->assertSee('QRIS Standar Nasional');
        $response->assertSee('GoPay');
        $response->assertSee('DANA');
        $response->assertSee('OVO');
        $response->assertSee('ShopeePay');
        $response->assertSee('Unduh QR (.svg)');
        $response->assertSee('<svg', false);
        $response->assertSee('AK-CHECKOUT-888');
    }

    public function test_user_can_cancel_pending_transaction(): void
    {
        PaymentSettingService::updateSettings(['active_payment_mode' => 'xendit']);

        $trx = CatatanCash::create([
            'user_id' => $this->user->id,
            'no_referensi' => 'AK-CANCEL-777',
            'pesanan' => 'Top Up 10 Koin Area Kerja',
            'total' => 10000,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('tenant.bills.cancel', $trx->id));

        $response->assertRedirect();
        $this->assertEquals('cancelled', $trx->fresh()->status);
    }

    public function test_realtime_status_polling_endpoint(): void
    {
        PaymentSettingService::updateSettings(['active_payment_mode' => 'xendit']);

        $trx = CatatanCash::create([
            'user_id' => $this->user->id,
            'no_referensi' => 'AK-POLL-666',
            'pesanan' => 'Top Up 100 Koin Area Kerja',
            'total' => 100000,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        // Polling saat status pending
        $response = $this->getJson('/payments/status/'.$trx->no_referensi);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'reference_id' => 'AK-POLL-666',
                'status' => 'pending',
                'is_paid' => false,
            ]);

        // Simulasikan pembayaran lunas
        $trx->update([
            'status' => 'diterima',
            'paid_at' => now(),
        ]);

        $responsePaid = $this->getJson('/payments/status/'.$trx->no_referensi);
        $responsePaid->assertStatus(200)
            ->assertJson([
                'success' => true,
                'reference_id' => 'AK-POLL-666',
                'status' => 'diterima',
                'is_paid' => true,
            ]);
    }

    public function test_superadmin_can_update_payment_settings(): void
    {
        $adminUser = User::create([
            'username' => 'superadmin_test',
            'email' => 'superadmin_test@areakerja.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'verified' => 1,
            'status' => 0,
        ]);

        SuperAdmin::create([
            'user_id' => $adminUser->id,
            'nama' => 'Super Administrator',
            'username' => 'superadmin_test',
            'email' => 'superadmin_test@areakerja.com',
        ]);

        // 1. Pilih mode Transfer Manual & QRIS Statis
        $payloadManual = [
            'active_payment_mode' => 'manual',
            'payment_manual_instructions' => 'Instruksi transfer baru untuk testing.',
            'bank_names' => ['BCA', 'Mandiri'],
            'account_numbers' => ['111222333', '444555666'],
            'account_holders' => ['PT AREA KERJA', 'PT AREA KERJA'],
        ];

        $responseManual = $this->actingAs($adminUser)
            ->put(route('superadmin.paket-harga.update-payment-settings'), $payloadManual);

        $responseManual->assertRedirect(route('superadmin.paket-harga', ['tab' => 'pengaturan_pembayaran']));

        // Verifikasi mutually exclusive: Manual TRUE, Xendit FALSE
        $this->assertTrue(PaymentSettingService::isManualEnabled());
        $this->assertFalse(PaymentSettingService::isXenditEnabled());
        $this->assertEquals('Instruksi transfer baru untuk testing.', PaymentSettingService::getManualInstructions());
        $banks = PaymentSettingService::getManualBanks();
        $this->assertCount(2, $banks);
        $this->assertEquals('BCA', $banks[0]['bank_name']);

        // 2. Beralih ke mode Xendit Gateway
        $payloadXendit = [
            'active_payment_mode' => 'xendit',
        ];

        $responseXendit = $this->actingAs($adminUser)
            ->put(route('superadmin.paket-harga.update-payment-settings'), $payloadXendit);

        $responseXendit->assertRedirect(route('superadmin.paket-harga', ['tab' => 'pengaturan_pembayaran']));

        // Verifikasi mutually exclusive: Xendit TRUE, Manual FALSE
        $this->assertTrue(PaymentSettingService::isXenditEnabled());
        $this->assertFalse(PaymentSettingService::isManualEnabled());
    }

    public function test_candidate_registration_checkout_renders_custom_payment_gateway_checkout(): void
    {
        $pelamarUser = User::create([
            'username' => 'pelamar_kandidat_test',
            'email' => 'pelamar_kandidat@areakerja.com',
            'password' => Hash::make('password123'),
            'role' => 'pelamar',
            'verified' => 1,
            'status' => 0,
        ]);

        Pelamar::create([
            'user_id' => $pelamarUser->id,
            'nama_pelamar' => 'Testing Pelamar',
            'telepon_pelamar' => '081234567890',
        ]);

        PaymentSettingService::updateSettings(['active_payment_mode' => 'xendit']);

        $trx = CatatanCash::create([
            'user_id' => $pelamarUser->id,
            'no_referensi' => 'INV-KANDIDAT-12345',
            'pesanan' => 'Pendaftaran Kandidat',
            'total' => 200000,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        $response = $this->actingAs($pelamarUser)->get(route('kandidat.transaksi', $trx->id));

        $response->assertStatus(200);
        $response->assertSee('Pilih Metode Pembayaran');
        $response->assertSee('QRIS Standar Nasional');
        $response->assertSee('INV-KANDIDAT-12345');
        $response->assertSee('Rp 200.000');
        $response->assertSee('<svg', false);
    }
}

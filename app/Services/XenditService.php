<?php

namespace App\Services;

use App\Models\CatatanCash;
use App\Models\Notifikasi;
use App\Services\CoinTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\InvoiceApi;

class XenditService
{
    protected string $secretKey;
    protected string $webhookToken;

    public function __construct()
    {
        $this->secretKey    = (string) config('services.xendit.secret_key', '');
        $this->webhookToken = (string) config('services.xendit.webhook_token', '');

        if (!empty($this->secretKey)) {
            Configuration::setXenditKey($this->secretKey);
        }
    }

    /**
     * Buat invoice pembayaran di Xendit untuk transaksi CatatanCash.
     */
    public function createInvoice(CatatanCash $transaksi, ?string $successUrl = null, ?string $failureUrl = null): array
    {
        if (empty($this->secretKey)) {
            Log::warning("Xendit Secret Key belum dikonfigurasi di .env / services.php.");
            return [
                'success' => false,
                'message' => 'Xendit secret key belum diatur.',
            ];
        }

        try {
            Configuration::setXenditKey($this->secretKey);
            $apiInstance = new InvoiceApi();

            $user = $transaksi->user;
            $payerEmail = $user?->email ?? 'pelanggan@areakerja.com';
            $customerName = $user?->nama_lengkap ?? ($user?->username ?? 'Pelanggan AreaKerja');

            $amount = max(1000, (float)($transaksi->total ?? 10000));
            $externalId = $transaksi->no_referensi ?: 'AK-' . time() . '-' . $transaksi->id;

            $params = [
                'external_id'           => $externalId,
                'amount'                => $amount,
                'description'           => $transaksi->pesanan ?: 'Pembayaran Transaksi AreaKerja',
                'payer_email'           => $payerEmail,
                'invoice_duration'      => 86400, // 24 jam
                'currency'              => 'IDR',
                'should_send_email'     => true,
                'success_redirect_url'  => $successUrl ?: route('catatan_cash.show', $transaksi->id),
                'failure_redirect_url'  => $failureUrl ?: route('catatan_cash.show', $transaksi->id),
            ];

            $createInvoiceRequest = new CreateInvoiceRequest($params);
            $invoice = $apiInstance->createInvoice($createInvoiceRequest);

            $invoiceId  = $invoice->getId();
            $invoiceUrl = $invoice->getInvoiceUrl();
            $status     = strtolower($invoice->getStatus() ?? 'pending');

            $transaksi->update([
                'xendit_id'    => $invoiceId,
                'checkout_url' => $invoiceUrl,
                'no_referensi' => $externalId,
                'status'       => ($status === 'paid' || $status === 'settled') ? 'diterima' : 'pending',
                'expired_at'   => Carbon::now()->addHours(24),
            ]);

            return [
                'success'     => true,
                'id'          => $invoiceId,
                'invoice_url' => $invoiceUrl,
                'status'      => $status,
            ];
        } catch (\Throwable $e) {
            Log::error("Gagal membuat Xendit Invoice untuk transaksi {$transaksi->id}: " . $e->getMessage(), [
                'transaksi_id' => $transaksi->id,
                'trace'        => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Memproses Webhook Callback dari Xendit.
     */
    public function handleWebhook(array $payload, ?string $callbackToken = null): array
    {
        // 1. Verifikasi callback token jika telah dikonfigurasi
        if (!empty($this->webhookToken) && $callbackToken !== $this->webhookToken) {
            Log::warning("Xendit Webhook Token tidak cocok.", [
                'expected' => substr($this->webhookToken, 0, 6) . '...',
                'received' => substr((string)$callbackToken, 0, 6) . '...',
            ]);
            return [
                'success' => false,
                'message' => 'Invalid callback token.',
                'code'    => 403,
            ];
        }

        $externalId = $payload['external_id'] ?? null;
        $xenditId   = $payload['id'] ?? null;
        $rawStatus  = strtoupper($payload['status'] ?? '');

        if (!$externalId && !$xenditId) {
            return [
                'success' => false,
                'message' => 'Missing external_id or id in payload.',
                'code'    => 400,
            ];
        }

        // Cari transaksi berdasarkan no_referensi atau xendit_id
        $transaksi = CatatanCash::where('no_referensi', $externalId)
            ->orWhere('xendit_id', $xenditId)
            ->first();

        if (!$transaksi) {
            Log::warning("Xendit Webhook: Transaksi tidak ditemukan.", ['external_id' => $externalId, 'xendit_id' => $xenditId]);
            return [
                'success' => false,
                'message' => 'Transaction not found.',
                'code'    => 404,
            ];
        }

        if (in_array($rawStatus, ['PAID', 'SETTLED'])) {
            $this->fulfillTransaction($transaksi, $payload);
        } elseif ($rawStatus === 'EXPIRED') {
            $transaksi->update(['status' => 'expired']);
        } elseif (in_array($rawStatus, ['FAILED', 'CANCELLED'])) {
            $transaksi->update(['status' => 'ditolak']);
        }

        return [
            'success' => true,
            'message' => 'Webhook processed successfully.',
            'status'  => $transaksi->fresh()->status,
            'code'    => 200,
        ];
    }

    /**
     * Eksekusi pemenuhan otomatis ketika pembayaran diterima (Settled / Paid).
     */
    public function fulfillTransaction(CatatanCash $transaksi, array $payload = []): void
    {
        if ($transaksi->status === 'diterima') {
            // Sudah diproses sebelumnya (idempotent)
            return;
        }

        DB::transaction(function () use ($transaksi, $payload) {
            $paymentMethod = $payload['payment_method'] ?? ($payload['payment_channel'] ?? $transaksi->sumberDana ?? 'Xendit Gateway');

            $transaksi->update([
                'status'     => 'diterima',
                'paid_at'    => Carbon::now(),
                'sumberDana' => $paymentMethod,
            ]);

            $user       = $transaksi->user;
            $perusahaan = $user?->perusahaan;
            $pelamar    = $user?->pelamar;

            $pesananLower = strtolower($transaksi->pesanan ?? '');
            $isTopUpKoin  = str_contains($pesananLower, 'koin') || str_contains($pesananLower, 'top up') || ($perusahaan !== null);
            $isPendaftaranKandidat = str_contains($pesananLower, 'kandidat') || ($pelamar !== null && $perusahaan === null);

            // 1. TOP UP KOIN PERUSAHAAN
            if ($isTopUpKoin && $perusahaan) {
                $jumlahKoin = (int)($transaksi->hargaPembayaran->jumlah_koin ?? 100);
                if ($jumlahKoin > 0) {
                    $coinService = app(CoinTransactionService::class);
                    $coinService->credit(
                        $perusahaan,
                        $jumlahKoin,
                        'Top Up ' . $jumlahKoin . ' Koin',
                        'Top Up Cash (' . $paymentMethod . ')',
                        $transaksi->no_referensi,
                        $perusahaan->nama_perusahaan ?? $transaksi->dari
                    );
                }
            }

            // 2. PENDAFTARAN KANDIDAT PELAMAR
            if ($isPendaftaranKandidat && $pelamar) {
                $pelamar->kategori = 'calon kandidat';
                $pelamar->save();
            }

            // 3. KIRIM NOTIFIKASI
            Notifikasi::create([
                'user_id'       => $transaksi->user_id,
                'perusahaan_id' => $perusahaan?->id,
                'judul'         => 'Pembayaran Berhasil Diverifikasi',
                'pesan'         => 'Pembayaran untuk pesanan "' . $transaksi->pesanan . '" berhasil diterima otomatis melalui Xendit (' . $paymentMethod . ').',
                'is_read'       => 0,
                'expired_at'    => Carbon::now()->addDays(7),
            ]);
        });
    }

    /**
     * Cek status invoice terkini ke Xendit secara sinkron (ketika user kembali ke web).
     */
    public function syncInvoiceStatus(CatatanCash $transaksi): CatatanCash
    {
        if ($transaksi->status === 'diterima' || empty($transaksi->xendit_id) || empty($this->secretKey)) {
            return $transaksi;
        }

        try {
            Configuration::setXenditKey($this->secretKey);
            $apiInstance = new InvoiceApi();
            $invoice = $apiInstance->getInvoiceById($transaksi->xendit_id);

            $status = strtoupper($invoice->getStatus() ?? '');
            if (in_array($status, ['PAID', 'SETTLED'])) {
                $paymentMethod = method_exists($invoice, 'getPaymentMethod') ? $invoice->getPaymentMethod() : 'Xendit';
                $paymentDestination = method_exists($invoice, 'getPaymentDestination') ? $invoice->getPaymentDestination() : null;
                $this->fulfillTransaction($transaksi, [
                    'payment_method'  => $paymentMethod,
                    'payment_channel' => $paymentDestination,
                ]);
            } elseif ($status === 'EXPIRED') {
                $transaksi->update(['status' => 'expired']);
            }
        } catch (\Throwable $e) {
            Log::info("Sinkronisasi status invoice Xendit ({$transaksi->xendit_id}) dilewati: " . $e->getMessage());
        }

        return $transaksi->fresh();
    }
}


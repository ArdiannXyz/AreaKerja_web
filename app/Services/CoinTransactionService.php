<?php

namespace App\Services;

use App\Models\CatatanKoin;
use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class CoinTransactionService
{
    /**
     * Potong saldo koin perusahaan secara atomik dan catat mutasi.
     *
     * @param Perusahaan $perusahaan
     * @param int $amount Jumlah koin yang dipotong (harus bernilai positif)
     * @param string $orderDescription Deskripsi pemesanan
     * @param string $source Sumber pemotongan (contoh: 'Pembelian Paket', 'Iklan')
     * @param string|null $reference Nomor referensi unik transaksi
     * @param string|null $from Pihak pengirim / nama perusahaan
     * @return CatatanKoin
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function deduct(
        Perusahaan $perusahaan,
        int $amount,
        string $orderDescription,
        string $source,
        ?string $reference = null,
        ?string $from = null
    ): CatatanKoin {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Jumlah koin yang dipotong harus lebih besar dari 0.");
        }

        return DB::transaction(function () use ($perusahaan, $amount, $orderDescription, $source, $reference, $from) {
            // Lock row perusahaan untuk mencegah race conditions
            $lockedPerusahaan = Perusahaan::where('id', $perusahaan->id)->lockForUpdate()->firstOrFail();

            if ($lockedPerusahaan->koin_perusahaan < $amount) {
                throw new RuntimeException("Saldo koin perusahaan tidak mencukupi.");
            }

            $lockedPerusahaan->decrement('koin_perusahaan', $amount);

            $ref = $reference ?: 'KOIN-DEC-' . strtoupper(Str::random(10));
            $fromName = $from ?: ($lockedPerusahaan->nama_perusahaan ?? 'Perusahaan');

            $catatan = CatatanKoin::create([
                'user_id'      => $lockedPerusahaan->user_id,
                'no_referensi' => $ref,
                'pesanan'      => $orderDescription,
                'dari'         => $fromName,
                'sumber_dana'  => $source,
                'total'        => '-' . $amount,
            ]);

            Log::info("Koin berhasil dipotong", [
                'perusahaan_id' => $lockedPerusahaan->id,
                'amount'        => $amount,
                'remaining'     => $lockedPerusahaan->fresh()->koin_perusahaan,
                'reference'     => $ref,
            ]);

            return $catatan;
        });
    }

    /**
     * Tambah saldo koin perusahaan (top-up / kredit) secara atomik dan catat mutasi.
     *
     * @param Perusahaan $perusahaan
     * @param int $amount Jumlah koin yang ditambahkan (harus bernilai positif)
     * @param string $orderDescription Deskripsi penambahan
     * @param string $source Sumber dana transaksi
     * @param string|null $reference Nomor referensi unik transaksi
     * @param string|null $from Pihak pengirim / keterangan sumber
     * @return CatatanKoin
     * @throws InvalidArgumentException
     */
    public function credit(
        Perusahaan $perusahaan,
        int $amount,
        string $orderDescription,
        string $source,
        ?string $reference = null,
        ?string $from = null
    ): CatatanKoin {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Jumlah koin yang ditambahkan harus lebih besar dari 0.");
        }

        return DB::transaction(function () use ($perusahaan, $amount, $orderDescription, $source, $reference, $from) {
            $lockedPerusahaan = Perusahaan::where('id', $perusahaan->id)->lockForUpdate()->firstOrFail();
            $lockedPerusahaan->increment('koin_perusahaan', $amount);

            $ref = $reference ?: 'KOIN-CRE-' . strtoupper(Str::random(10));
            $fromName = $from ?: ($lockedPerusahaan->nama_perusahaan ?? 'Sistem AreaKerja');

            $catatan = CatatanKoin::firstOrCreate(
                ['no_referensi' => $ref],
                [
                    'user_id'     => $lockedPerusahaan->user_id,
                    'pesanan'     => $orderDescription,
                    'dari'        => $fromName,
                    'sumber_dana' => $source,
                    'total'       => '+' . $amount,
                ]
            );

            Log::info("Koin berhasil ditambahkan", [
                'perusahaan_id' => $lockedPerusahaan->id,
                'amount'        => $amount,
                'current_total' => $lockedPerusahaan->fresh()->koin_perusahaan,
                'reference'     => $ref,
            ]);

            return $catatan;
        });
    }

    /**
     * Kembalikan saldo koin perusahaan (refund) secara atomik.
     *
     * @param Perusahaan $perusahaan
     * @param int $amount Jumlah koin yang di-refund
     * @param string $orderDescription Alasan/keterangan refund
     * @param string $source Asal refund
     * @param string|null $reference
     * @param string $from
     * @return CatatanKoin
     */
    public function refund(
        Perusahaan $perusahaan,
        int $amount,
        string $orderDescription,
        string $source = 'Refund Koin',
        ?string $reference = null,
        string $from = 'Sistem AreaKerja'
    ): CatatanKoin {
        if ($amount <= 0) {
            throw new InvalidArgumentException("Jumlah refund koin harus lebih besar dari 0.");
        }

        $ref = $reference ?: 'REFUND-' . strtoupper(Str::random(10));
        return $this->credit($perusahaan, $amount, $orderDescription, $source, $ref, $from);
    }
}


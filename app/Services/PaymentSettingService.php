<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaymentSettingService
{
    public const KEY_XENDIT_ENABLED = 'payment_xendit_enabled';

    public const KEY_MANUAL_ENABLED = 'payment_manual_enabled';

    public const KEY_MANUAL_QR_IMAGE = 'payment_manual_qr_image';

    public const KEY_MANUAL_BANKS = 'payment_manual_banks';

    public const KEY_MANUAL_INSTRUCTIONS = 'payment_manual_instructions';

    /**
     * Default daftar rekening transfer manual bank nasional.
     */
    public static function getDefaultBanks(): array
    {
        return [
            [
                'bank_name' => 'BCA',
                'account_number' => '8040192837',
                'account_holder' => 'PT AREA KERJA INDONESIA',
                'logo' => 'images/bank/bca.png',
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '1370019283741',
                'account_holder' => 'PT AREA KERJA INDONESIA',
                'logo' => 'images/bank/mandiri.png',
            ],
            [
                'bank_name' => 'BRI',
                'account_number' => '034101000123301',
                'account_holder' => 'PT AREA KERJA INDONESIA',
                'logo' => 'images/bank/bri.png',
            ],
            [
                'bank_name' => 'BNI',
                'account_number' => '0819283741',
                'account_holder' => 'PT AREA KERJA INDONESIA',
                'logo' => 'images/bank/bni.png',
            ],
        ];
    }

    public static function getDefaultInstructions(): string
    {
        return "1. Lakukan transfer sesuai nominal tagihan hingga 3 digit terakhir.\n2. Simpan bukti transfer atau tangkapan layar m-Banking.\n3. Unggah bukti pembayaran melalui form konfirmasi transfer manual di bawah ini.\n4. Admin Finance AreaKerja akan memverifikasi pembayaran Anda maksimal 1x24 jam kerja.";
    }

    public static function isXenditEnabled(): bool
    {
        $val = SiteSetting::get(self::KEY_XENDIT_ENABLED, '0');

        return in_array($val, ['1', 'true', 'on', true], true);
    }

    /**
     * Metode pembayaran bersifat mutually exclusive (hanya salah satu yang aktif).
     * Jika Xendit aktif, maka Manual nonaktif. Jika Xendit nonaktif, maka Manual aktif.
     */
    public static function isManualEnabled(): bool
    {
        if (self::isXenditEnabled()) {
            return false;
        }

        return true;
    }

    public static function getManualBanks(): array
    {
        $raw = SiteSetting::get(self::KEY_MANUAL_BANKS);
        if (! empty($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }

        return self::getDefaultBanks();
    }

    public static function getManualInstructions(): string
    {
        return SiteSetting::get(self::KEY_MANUAL_INSTRUCTIONS, self::getDefaultInstructions());
    }

    public static function getManualQrImageUrl(): ?string
    {
        $path = SiteSetting::get(self::KEY_MANUAL_QR_IMAGE);
        if ($path && Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        return null;
    }

    /**
     * Update konfigurasi pembayaran dari Superadmin (Mutually Exclusive).
     */
    public static function updateSettings(array $data, ?UploadedFile $qrImageFile = null): void
    {
        $mode = $data['active_payment_mode'] ?? null;
        if (! $mode) {
            if (! empty($data['payment_xendit_enabled']) && empty($data['payment_manual_enabled'])) {
                $mode = 'xendit';
            } elseif (! empty($data['payment_manual_enabled']) && empty($data['payment_xendit_enabled'])) {
                $mode = 'manual';
            } else {
                $mode = ! empty($data['payment_xendit_enabled']) ? 'xendit' : 'manual';
            }
        }

        if ($mode === 'xendit') {
            SiteSetting::set(self::KEY_XENDIT_ENABLED, '1');
            SiteSetting::set(self::KEY_MANUAL_ENABLED, '0');
        } else {
            SiteSetting::set(self::KEY_XENDIT_ENABLED, '0');
            SiteSetting::set(self::KEY_MANUAL_ENABLED, '1');
        }

        if (isset($data['payment_manual_instructions'])) {
            SiteSetting::set(self::KEY_MANUAL_INSTRUCTIONS, trim($data['payment_manual_instructions']));
        }

        if (isset($data['payment_manual_banks'])) {
            $banks = is_array($data['payment_manual_banks'])
                ? $data['payment_manual_banks']
                : json_decode($data['payment_manual_banks'], true);
            if (is_array($banks)) {
                SiteSetting::set(self::KEY_MANUAL_BANKS, json_encode($banks));
            }
        }

        if ($qrImageFile) {
            $path = $qrImageFile->store('settings/payment', 'public');
            SiteSetting::set(self::KEY_MANUAL_QR_IMAGE, $path);
        }
    }
}

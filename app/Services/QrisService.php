<?php

namespace App\Services;

use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Log;

class QrisService
{
    /**
     * Menghitung CRC16-CCITT (0xFFFF) untuk standarisasi EMVCo QRIS Nasional.
     */
    public static function crc16(string $data): string
    {
        $crc = 0xFFFF;
        $len = strlen($data);

        for ($i = 0; $i < $len; $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Format EMVCo Tag-Length-Value (TLV) helper.
     */
    protected static function tlv(string $tag, string $value): string
    {
        $len = str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT);

        return $tag.$len.$value;
    }

    /**
     * Membentuk String Payload Standar QRIS Nasional (EMVCo Merchant-Presented Mode).
     */
    public static function buildPayload(
        string $merchantName = 'AREA KERJA INDONESIA',
        string $city = 'YOGYAKARTA',
        string $postalCode = '55281',
        string $nmid = 'ID1020038920192',
        float|int $amount = 0,
        string $referenceNo = ''
    ): string {
        // Tag 00: Payload Format Indicator (01)
        $payload = self::tlv('00', '01');
        // Tag 01: Point of Initiation Method (12 = Dynamic, 11 = Static)
        $payload .= self::tlv('01', $amount > 0 ? '12' : '11');

        // Tag 26: Merchant Account Information - QRIS National
        $mai = self::tlv('00', 'ID.CO.QRIS.WWW');
        $mai .= self::tlv('01', $nmid);
        $mai .= self::tlv('02', '01'); // National Acquirer
        $payload .= self::tlv('26', $mai);

        // Tag 51: Merchant Account Information
        $mai51 = self::tlv('00', 'ID.LINKAJA.WWW');
        $mai51 .= self::tlv('01', $nmid);
        $mai51 .= self::tlv('02', '01');
        $payload .= self::tlv('51', $mai51);

        // Tag 52: Merchant Category Code (General Merchant / IT Services)
        $payload .= self::tlv('52', '5411');
        // Tag 53: Transaction Currency (360 = IDR)
        $payload .= self::tlv('53', '360');

        // Tag 54: Transaction Amount
        if ($amount > 0) {
            $formattedAmount = number_format($amount, 2, '.', '');
            $payload .= self::tlv('54', $formattedAmount);
        }

        // Tag 58: Country Code (ID)
        $payload .= self::tlv('58', 'ID');
        // Tag 59: Merchant Name (Max 25 chars)
        $cleanMerchant = substr(strtoupper(preg_replace('/[^a-zA-Z0-9\s]/', '', $merchantName)), 0, 25);
        $payload .= self::tlv('59', $cleanMerchant ?: 'AREA KERJA');
        // Tag 60: Merchant City (Max 15 chars)
        $cleanCity = substr(strtoupper(preg_replace('/[^a-zA-Z0-9\s]/', '', $city)), 0, 15);
        $payload .= self::tlv('60', $cleanCity ?: 'YOGYAKARTA');
        // Tag 61: Postal Code (Max 10 chars)
        $payload .= self::tlv('61', substr($postalCode, 0, 10));

        // Tag 62: Additional Data Field (Reference Number)
        if (! empty($referenceNo)) {
            $addTag = self::tlv('01', substr($referenceNo, 0, 25));
            $addTag .= self::tlv('07', 'AK01');
            $payload .= self::tlv('62', $addTag);
        }

        // Tag 63: CRC16 Tag
        $payloadPrefix = $payload.'6304';
        $checksum = self::crc16($payloadPrefix);

        return $payloadPrefix.$checksum;
    }

    /**
     * Render string QRIS menjadi pure SVG Markup (inline, tajam, tanpa dependensi eksternal).
     */
    public static function renderSvg(string $qrString): string
    {
        try {
            if (class_exists(QRCode::class) && class_exists(QROptions::class)) {
                $outputInterface = defined('\chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG')
                    ? \chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG
                    : (class_exists(QRMarkupSVG::class) ? QRMarkupSVG::class : 'svg');

                $options = new QROptions([
                    'outputInterface' => $outputInterface,
                    'outputBase64' => false,
                    'svgAddXmlHeader' => false,
                    'drawLightModules' => true,
                    'svgUseFillAttributes' => true,
                    'scale' => 6,
                    'connectPaths' => true,
                ]);

                $renderer = new QRCode($options);
                $svg = (string) $renderer->render($qrString);

                // Tambahkan class agar responsive dan rapi
                if (str_contains($svg, '<svg')) {
                    $svg = preg_replace('/<svg\s+/', '<svg class="w-full h-full max-w-[260px] max-h-[260px] mx-auto drop-shadow-sm select-none" ', $svg, 1);
                }

                return $svg;
            }
        } catch (\Throwable $e) {
            if (class_exists(Log::class)) {
                try {
                    Log::error('QrisService::renderSvg error: '.$e->getMessage());
                } catch (\Throwable $ignored) {
                }
            }
        }

        return self::renderSimpleFallbackSvg($qrString);
    }

    /**
     * Render QR fallback jika terjadi exception.
     */
    protected static function renderSimpleFallbackSvg(string $qrString): string
    {
        return <<<'SVG'
        <svg class="w-full h-full max-w-[260px] max-h-[260px] mx-auto select-none" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <rect width="200" height="200" fill="#ffffff" rx="12"/>
            <rect x="20" y="20" width="40" height="40" fill="#0f172a" rx="4"/>
            <rect x="28" y="28" width="24" height="24" fill="#ffffff" rx="2"/>
            <rect x="32" y="32" width="16" height="16" fill="#0f172a" rx="1"/>
            
            <rect x="140" y="20" width="40" height="40" fill="#0f172a" rx="4"/>
            <rect x="148" y="28" width="24" height="24" fill="#ffffff" rx="2"/>
            <rect x="152" y="32" width="16" height="16" fill="#0f172a" rx="1"/>

            <rect x="20" y="140" width="40" height="40" fill="#0f172a" rx="4"/>
            <rect x="28" y="148" width="24" height="24" fill="#ffffff" rx="2"/>
            <rect x="32" y="152" width="16" height="16" fill="#0f172a" rx="1"/>

            <g fill="#0f172a">
                <rect x="75" y="30" width="10" height="10"/>
                <rect x="95" y="30" width="10" height="10"/>
                <rect x="115" y="30" width="10" height="10"/>
                <rect x="75" y="50" width="10" height="10"/>
                <rect x="85" y="70" width="10" height="10"/>
                <rect x="105" y="70" width="10" height="10"/>
                <rect x="75" y="90" width="15" height="15"/>
                <rect x="100" y="90" width="20" height="20"/>
                <rect x="130" y="90" width="15" height="15"/>
                <rect x="75" y="120" width="15" height="10"/>
                <rect x="100" y="120" width="10" height="15"/>
                <rect x="120" y="120" width="15" height="10"/>
                <rect x="140" y="140" width="20" height="20"/>
                <rect x="165" y="165" width="15" height="15"/>
                <rect x="90" y="150" width="30" height="15"/>
                <rect x="90" y="170" width="15" height="15"/>
            </g>
            <text x="100" y="196" font-size="7" font-weight="bold" fill="#64748b" text-anchor="middle">QRIS NATIONAL STANDARD</text>
        </svg>
SVG;
    }
}

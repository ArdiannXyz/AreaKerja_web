<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class WilayahService
{
    private static ?array $provinces = null;
    private static ?array $regencies = null;
    private static ?array $districts = null;

    /**
     * Membaca dan membersihkan file JSON dari disk dengan memoization in-memory.
     */
    private static function loadJson(string $filename): array
    {
        $filepath = database_path("data/{$filename}");
        if (!file_exists($filepath)) {
            return [];
        }

        $content = file_get_contents($filepath);
        if ($content === false) {
            return [];
        }

        // Hapus UTF-8 BOM jika ada
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = trim($content);

        $data = json_decode($content, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
            $data = json_decode($content, true);
        }

        return is_array($data) ? $data : [];
    }

    /**
     * Ambil seluruh provinsi (di-cache di memory).
     */
    public static function getProvinces(): Collection
    {
        if (self::$provinces === null) {
            $raw = self::loadJson('provinces.json');
            self::$provinces = collect($raw)->map(function ($p) {
                return (object)[
                    'id'       => (string)($p['id'] ?? ''),
                    'nama'     => ucwords(strtolower($p['name'] ?? '')),
                    'name'     => $p['name'] ?? '',
                    'alt_name' => $p['alt_name'] ?? '',
                ];
            })->all();
        }

        return collect(self::$provinces);
    }

    /**
     * Cari provinsi berdasarkan ID atau nama.
     */
    public static function findProvince(string|int|null $query): ?object
    {
        if (empty($query)) {
            return null;
        }

        $input = trim((string)$query);
        $provinces = self::getProvinces();

        return $provinces->first(function ($p) use ($input) {
            return $p->id === $input
                || (is_numeric($input) && (int)$p->id === (int)$input)
                || strcasecmp($p->nama, $input) === 0
                || strcasecmp($p->name, $input) === 0
                || ($p->alt_name && strcasecmp($p->alt_name, $input) === 0)
                || str_contains(strtolower($p->nama), strtolower($input));
        });
    }

    /**
     * Ambil seluruh kabupaten/kota, opsional filter berdasarkan province_id.
     */
    public static function getRegencies(string|int|null $provinceId = null): Collection
    {
        if (self::$regencies === null) {
            $raw = self::loadJson('regencies.json');
            self::$regencies = collect($raw)->map(function ($r) {
                return (object)[
                    'id'          => (string)($r['id'] ?? ''),
                    'province_id' => (string)($r['province_id'] ?? ''),
                    'nama'        => ucwords(strtolower($r['name'] ?? '')),
                    'name'        => $r['name'] ?? '',
                    'alt_name'    => $r['alt_name'] ?? '',
                ];
            })->all();
        }

        $collection = collect(self::$regencies);
        if ($provinceId !== null && $provinceId !== '') {
            $provStr = (string)$provinceId;
            return $collection->filter(fn($r) => (string)$r->province_id === $provStr)->values();
        }

        return $collection;
    }

    /**
     * Cari kabupaten/kota berdasarkan ID atau nama.
     */
    public static function findRegency(string|int|null $query): ?object
    {
        if (empty($query)) {
            return null;
        }

        $input = trim((string)$query);
        $regencies = self::getRegencies();

        return $regencies->first(function ($r) use ($input) {
            return $r->id === $input
                || (is_numeric($input) && (int)$r->id === (int)$input)
                || strcasecmp($r->nama, $input) === 0
                || strcasecmp($r->name, $input) === 0
                || ($r->alt_name && strcasecmp($r->alt_name, $input) === 0);
        });
    }

    /**
     * Ambil seluruh kecamatan, opsional filter berdasarkan regency_id.
     */
    public static function getDistricts(string|int|null $regencyId = null): Collection
    {
        if (self::$districts === null) {
            $raw = self::loadJson('districts.json');
            self::$districts = collect($raw)->map(function ($d) {
                return (object)[
                    'id'         => (string)($d['id'] ?? ''),
                    'regency_id' => (string)($d['regency_id'] ?? ''),
                    'nama'       => ucwords(strtolower($d['name'] ?? '')),
                    'name'       => $d['name'] ?? '',
                    'alt_name'   => $d['alt_name'] ?? '',
                ];
            })->all();
        }

        $collection = collect(self::$districts);
        if ($regencyId !== null && $regencyId !== '') {
            $regStr = (string)$regencyId;
            return $collection->filter(fn($d) => (string)$d->regency_id === $regStr)->values();
        }

        return $collection;
    }

    /**
     * Cari kecamatan berdasarkan ID atau nama.
     */
    public static function findDistrict(string|int|null $query): ?object
    {
        if (empty($query)) {
            return null;
        }

        return $districts->first(function ($d) use ($input) {
            return $d->id === $input
                || (is_numeric($input) && (int)$d->id === (int)$input)
                || strcasecmp($d->nama, $input) === 0
                || strcasecmp($d->name, $input) === 0;
        });
    }

    /**
     * Helper response terformat untuk list kota berdasarkan provinsi
     */
    public static function getRegenciesJson(string|int|null $provinsiId): array
    {
        $input = trim(urldecode((string)$provinsiId));
        if (empty($input)) {
            return [];
        }

        $province = self::findProvince($input);
        $targetProvId = $province ? $province->id : $input;

        return self::getRegencies($targetProvId)->map(function ($item) {
            return [
                'id'          => (string)$item->id,
                'provinsi_id' => (string)$item->province_id,
                'nama'        => $item->nama,
            ];
        })->values()->all();
    }

    /**
     * Helper response terformat untuk list kecamatan berdasarkan kota
     */
    public static function getDistrictsJson(string|int|null $kotaId): array
    {
        $input = trim(urldecode((string)$kotaId));
        if (empty($input)) {
            return [];
        }

        $regency = self::findRegency($input);
        $targetKotaId = $regency ? $regency->id : $input;

        return self::getDistricts($targetKotaId)->map(function ($item) {
            return [
                'id'      => (string)$item->id,
                'kota_id' => (string)$item->regency_id,
                'nama'    => $item->nama,
            ];
        })->values()->all();
    }
}


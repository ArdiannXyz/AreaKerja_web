@php
    $perusahaan = $lowongan->perusahaan ?? null;

    $rawDescription = strip_tags(
        (string) ($lowongan->deskripsi ?? ''),
        '<p><br><ul><ol><li><b><strong><i><em>'
    );
    $cleanDescription = trim($rawDescription) !== ''
        ? trim($rawDescription)
        : ($lowongan->nama ?? 'Lowongan Kerja');

    $datePosted = $lowongan->published_at ?? $lowongan->created_at;

    $validThrough = null;
    if ($lowongan->expired_at) {
        $validThrough = $lowongan->expired_at->toIso8601String();
    } elseif ($lowongan->batas_lamaran) {
        $validThrough = \Carbon\Carbon::parse($lowongan->batas_lamaran)->endOfDay()->toIso8601String();
    }

    $jobPosting = [
        '@context' => 'https://schema.org/',
        '@type' => 'JobPosting',
        'title' => $lowongan->nama,
        'description' => $cleanDescription,
    ];

    if ($datePosted) {
        $jobPosting['datePosted'] = $datePosted->toIso8601String();
    }

    if ($validThrough) {
        $jobPosting['validThrough'] = $validThrough;
    }

    if ($perusahaan) {
        $hiringOrg = [
            '@type' => 'Organization',
            'name' => $perusahaan->nama_perusahaan,
        ];

        if (! empty($perusahaan->website_perusahaan)) {
            $website = trim((string) $perusahaan->website_perusahaan);
            if (! preg_match('#^https?://#i', $website)) {
                $website = 'https://' . $website;
            }
            if (filter_var($website, FILTER_VALIDATE_URL)) {
                $hiringOrg['sameAs'] = $website;
            }
        }

        if (! empty($perusahaan->img_profile)) {
            $hiringOrg['logo'] = asset('storage/' . $perusahaan->img_profile);
        }

        $jobPosting['hiringOrganization'] = $hiringOrg;
    }

    $alamatUtama = $perusahaan?->alamat_utama;
    $addressLocality = $alamatUtama->kota->nama ?? $perusahaan?->kota ?? null;
    $addressRegion = $alamatUtama->provinsi->nama ?? $perusahaan?->provinsi ?? null;
    $streetAddress = $lowongan->alamat ?: ($alamatUtama->detail ?? null);

    $postalAddress = array_filter([
        '@type' => 'PostalAddress',
        'streetAddress' => $streetAddress ?: null,
        'addressLocality' => $addressLocality ?: null,
        'addressRegion' => $addressRegion ?: null,
        'addressCountry' => 'ID',
    ], fn ($value) => $value !== null && $value !== '');

    $jobPosting['jobLocation'] = [
        '@type' => 'Place',
        'address' => $postalAddress,
    ];

    $employmentTypeMap = [
        'full time' => 'FULL_TIME',
        'fulltime' => 'FULL_TIME',
        'part time' => 'PART_TIME',
        'middletime' => 'PART_TIME',
        'middle' => 'PART_TIME',
        'kontrak' => 'CONTRACTOR',
        'contract' => 'CONTRACTOR',
        'magang' => 'INTERN',
        'internship' => 'INTERN',
        'freelance' => 'TEMPORARY',
    ];

    $jenisKey = strtolower(trim((string) ($lowongan->jenis ?? '')));
    if ($jenisKey !== '' && isset($employmentTypeMap[$jenisKey])) {
        $jobPosting['employmentType'] = $employmentTypeMap[$jenisKey];
    }

    if (empty($lowongan->gaji_hidden)) {
        $gajiAwal = (int) preg_replace('/\D/', '', (string) ($lowongan->gaji_awal ?? ''));
        $gajiAkhir = (int) preg_replace('/\D/', '', (string) ($lowongan->gaji_akhir ?? ''));

        if ($gajiAwal > 0 || $gajiAkhir > 0) {
            $quantitativeValue = [
                '@type' => 'QuantitativeValue',
                'unitText' => 'MONTH',
            ];

            if ($gajiAwal > 0 && $gajiAkhir > 0 && $gajiAkhir >= $gajiAwal) {
                $quantitativeValue['minValue'] = $gajiAwal;
                $quantitativeValue['maxValue'] = $gajiAkhir;
            } else {
                $quantitativeValue['value'] = $gajiAwal > 0 ? $gajiAwal : $gajiAkhir;
            }

            $jobPosting['baseSalary'] = [
                '@type' => 'MonetaryAmount',
                'currency' => 'IDR',
                'value' => $quantitativeValue,
            ];
        }
    }

    $jsonLd = json_encode(
        $jobPosting,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT
    );
@endphp
@if ($jsonLd !== false)
<script type="application/ld+json">
{!! $jsonLd !!}
</script>
@endif

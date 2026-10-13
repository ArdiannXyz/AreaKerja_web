<?php

namespace Tests\Feature;

use App\Http\Requests\BeliKandidatRequest;
use App\Http\Requests\StoreIklanRequest;
use App\Http\Requests\StoreLowonganRequest;
use App\Http\Requests\UploadBuktiTransferRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class FormRequestValidationTest extends TestCase
{
    public function test_store_lowongan_request_rules(): void
    {
        $request = new StoreLowonganRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('nama', $rules);
        $this->assertArrayHasKey('alamat', $rules);
        $this->assertArrayHasKey('jenis', $rules);
        $this->assertArrayHasKey('kategori', $rules);
        $this->assertArrayHasKey('gaji_awal', $rules);
        $this->assertArrayHasKey('gaji_akhir', $rules);

        $validator = Validator::make([
            'nama' => 'Software Engineer',
            'alamat' => 'Yogyakarta',
            'jenis' => 'Full-time',
            'kategori' => 'IT & Software',
            'gaji_awal' => 5000000,
            'gaji_akhir' => 8000000,
            'deskripsi' => 'Deskripsi pekerjaan lengkap',
            'syarat_pekerjaan' => 'Syarat pengalaman 1 tahun',
            'batas_lamaran' => '2026-12-31',
        ], $rules);

        $this->assertTrue($validator->passes());
    }

    public function test_store_iklan_request_rules(): void
    {
        $request = new StoreIklanRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('paket_id', $rules);
        $this->assertArrayHasKey('judul_iklan', $rules);
        $this->assertArrayHasKey('gambar_banner', $rules);
        $this->assertArrayHasKey('url_tujuan', $rules);

        // Paket ID tidak valid (misal: 99) harus gagal
        $validator = Validator::make([
            'paket_id' => 99,
            'judul_iklan' => 'Promo Karir',
            'url_tujuan' => 'https://areakerja.com',
        ], $rules);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('paket_id', $validator->errors()->toArray());
    }

    public function test_upload_bukti_transfer_request_rules(): void
    {
        $request = new UploadBuktiTransferRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('bukti', $rules);

        $validator = Validator::make([], $rules);
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('bukti', $validator->errors()->toArray());
    }

    public function test_beli_kandidat_request_rules(): void
    {
        $request = new BeliKandidatRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('pelamar_id', $rules);
        $this->assertArrayHasKey('lowongan_perusahaan_id', $rules);
    }
}


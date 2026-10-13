<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLowonganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['perusahaan', 'super_admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama'             => 'required|string|max:255',
            'alamat'           => 'required|string|max:255',
            'jenis'            => 'required|string|max:100',
            'kategori'         => 'required|string|max:100',
            'label_gaji'       => 'nullable|string|max:100',
            'benefit'          => 'nullable|string',
            'gaji_awal'        => 'required|numeric|min:0',
            'gaji_akhir'       => 'required|numeric|min:0',
            'deskripsi'        => 'required|string',
            'tanggung_jawab'   => 'nullable|string',
            'syarat_pekerjaan' => 'required|string',
            'batas_lamaran'    => 'required|date',
            'paket_id'         => 'nullable|exists:paket_lowongans,id',
        ];
    }

    /**
     * Custom message for validation rules.
     */
    public function messages(): array
    {
        return [
            'nama.required'             => 'Nama posisi lowongan wajib diisi.',
            'alamat.required'           => 'Lokasi/alamat penempatan wajib diisi.',
            'jenis.required'            => 'Jenis pekerjaan wajib dipilih.',
            'kategori.required'         => 'Kategori pekerjaan wajib dipilih.',
            'gaji_awal.required'        => 'Gaji awal wajib diisi dengan nominal angka valid.',
            'gaji_akhir.required'       => 'Gaji akhir wajib diisi dengan nominal angka valid.',
            'deskripsi.required'        => 'Deskripsi lowongan pekerjaan wajib diisi.',
            'syarat_pekerjaan.required' => 'Persyaratan pekerjaan wajib diisi.',
            'batas_lamaran.required'    => 'Batas akhir lamaran wajib ditentukan.',
        ];
    }
}


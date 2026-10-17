<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePelamarReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'perusahaan';
    }

    public function rules(): array
    {
        return [
            'alasan'      => ['required', 'string', Rule::in(array_keys(Report::ALASAN_PELAMAR))],
            'deskripsi'   => ['required', 'string', 'min:10', 'max:2000'],
            'bukti'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'lowongan_id' => ['nullable', 'integer', 'exists:lowongan_perusahaans,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan.required'    => 'Pilih alasan laporan terhadap pelamar.',
            'alasan.in'          => 'Alasan laporan tidak valid.',
            'deskripsi.required' => 'Jelaskan kronologi atau alasan laporan secara rinci.',
            'deskripsi.min'      => 'Deskripsi minimal 10 karakter.',
            'deskripsi.max'      => 'Deskripsi maksimal 2000 karakter.',
            'bukti.file'         => 'Bukti harus berupa file dokumen atau gambar.',
            'bukti.mimes'        => 'Bukti harus berformat JPG, PNG, atau PDF.',
            'bukti.max'          => 'Ukuran bukti maksimal 5 MB.',
            'lowongan_id.exists' => 'Lowongan terkait tidak valid.',
        ];
    }
}


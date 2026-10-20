<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'pelamar';
    }

    public function rules(): array
    {
        return [
            'alasan'    => ['required', 'string', Rule::in(array_keys(Report::ALASAN))],
            'deskripsi' => ['required', 'string', 'min:10', 'max:1000'],
            'bukti'     => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan.required'    => 'Pilih alasan laporan.',
            'alasan.in'          => 'Alasan laporan tidak valid.',
            'deskripsi.required' => 'Jelaskan alasan laporan Anda.',
            'deskripsi.min'      => 'Deskripsi minimal 10 karakter.',
            'bukti.image'        => 'Bukti harus berupa gambar.',
            'bukti.max'          => 'Ukuran bukti maksimal 2 MB.',
        ];
    }
}

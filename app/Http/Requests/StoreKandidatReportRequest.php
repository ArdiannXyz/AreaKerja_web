<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKandidatReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'super_admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'alasan'      => ['required', 'string', Rule::in(array_keys(Report::ALASAN_PELAMAR))],
            'deskripsi'   => ['required', 'string', 'min:10', 'max:2000'],
            'bukti'       => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'lowongan_id' => ['nullable', 'integer', 'exists:lowongan_perusahaans,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'alasan.required'    => 'Pilih alasan laporan terhadap kandidat.',
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

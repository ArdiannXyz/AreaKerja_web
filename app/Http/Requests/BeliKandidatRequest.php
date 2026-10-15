<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeliKandidatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'perusahaan';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pelamar_id'             => 'required|exists:pelamars,id',
            'lowongan_perusahaan_id' => 'required|exists:lowongan_perusahaans,id',
        ];
    }

    /**
     * Custom message for validation rules.
     */
    public function messages(): array
    {
        return [
            'pelamar_id.required'             => 'Kandidat pelamar wajib dipilih.',
            'pelamar_id.exists'               => 'Data pelamar tidak ditemukan di sistem.',
            'lowongan_perusahaan_id.required' => 'Lowongan perusahaan penempatan wajib dipilih.',
            'lowongan_perusahaan_id.exists'   => 'Lowongan penempatan tidak ditemukan di sistem.',
        ];
    }
}


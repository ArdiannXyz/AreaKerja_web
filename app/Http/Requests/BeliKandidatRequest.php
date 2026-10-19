<?php

namespace App\Http\Requests;

use App\Models\PelamarLowongan;
use App\Models\PembelianCv;
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

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            $perusahaan = $user?->perusahaan;
            if (!$perusahaan) {
                return;
            }

            $pelamarId = $this->input('pelamar_id');
            if (!$pelamarId) {
                return;
            }

            // 1. Cek apakah CV kandidat sudah dibuka / diunduh
            $hasBoughtCv = PembelianCv::where('perusahaan_id', $perusahaan->id)
                ->where('pelamar_id', $pelamarId)
                ->exists();

            if (!$hasBoughtCv) {
                $validator->errors()->add('pelamar_id', 'Anda harus mengunduh CV kandidat terlebih dahulu sebelum dapat merekrutnya.');
            }

            // 2. Cek apakah kandidat sudah pernah direkrut sebelumnya oleh perusahaan ini
            $alreadyRecruited = PelamarLowongan::where('pelamar_id', $pelamarId)
                ->whereHas('lowongan_perusahaan', function ($q) use ($perusahaan) {
                    $q->where('perusahaan_id', $perusahaan->id);
                })
                ->whereIn('status', ['pending', 'diterima'])
                ->exists();

            if ($alreadyRecruited) {
                $validator->errors()->add('pelamar_id', 'Kandidat ini sudah pernah Anda rekrut.');
            }
        });
    }
}


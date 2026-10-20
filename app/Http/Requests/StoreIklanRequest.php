<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIklanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'perusahaan';
    }

    public function rules(): array
    {
        return [
            'paket_id'      => 'required|in:2,3,4',
            'judul_iklan'   => 'required|string|max:200',
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'url_tujuan'    => 'required|url|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'paket_id.required'      => 'Silakan pilih paket iklan.',
            'judul_iklan.required'   => 'Judul iklan wajib diisi.',
            'gambar_banner.required' => 'Gambar banner iklan wajib diunggah.',
            'gambar_banner.image'    => 'File banner harus berupa gambar.',
            'gambar_banner.max'      => 'Ukuran gambar banner maksimal 3MB.',
            'url_tujuan.required'    => 'Link tujuan iklan wajib diisi.',
            'url_tujuan.url'         => 'Format link tujuan harus berupa URL valid (cth: https://...).',
        ];
    }
}


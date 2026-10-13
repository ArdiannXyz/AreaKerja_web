<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePerusahaanRequest extends FormRequest
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
            'nama_perusahaan'     => 'nullable|string|max:255',
            'jenis_perusahaan'    => 'nullable|string|max:255',
            'website_perusahaan'  => 'nullable|string|max:255',
            'telepon_perusahaan'  => [
                'nullable',
                'regex:/^(?:\+628|08)[0-9]+$/',
            ],
            'whatsapp'            => [
                'nullable',
                'regex:/^(?:628|08)[0-9]+$/',
            ],
            'legalitas'           => 'nullable|string|max:255',
            'deskripsi'           => 'nullable|string',
            'visi'                => 'nullable|string',
            'misi'                => 'nullable|string',
            'alamat'              => 'nullable|string',
            'kota'                => 'nullable|string|max:255',
            'provinsi'            => 'nullable|string|max:255',
            'img_profile'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Custom message for validation rules.
     */
    public function messages(): array
    {
        return [
            'telepon_perusahaan.regex' => 'Nomor telepon harus diawali 08, atau +628.',
            'whatsapp.regex'           => 'Nomor WhatsApp harus diawali 08, atau 628.',
            'img_profile.image'        => 'File logo/foto profil harus berupa gambar.',
            'img_profile.mimes'        => 'Format gambar profil harus JPG, JPEG, atau PNG.',
            'img_profile.max'          => 'Ukuran gambar profil maksimal 2MB.',
        ];
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminIklanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'super_admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'judul_iklan'   => 'required|string|max:255',
            'url_tujuan'    => 'required|url|max:255',
            'gambar_banner' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'durasi_hari'   => 'required|integer|min:1|max:3650',
            'perusahaan_id' => 'nullable|exists:perusahaans,id',
        ];
    }

    /**
     * Custom message for validation errors.
     */
    public function messages(): array
    {
        return [
            'judul_iklan.required'   => 'Judul kampanye iklan wajib diisi.',
            'judul_iklan.max'        => 'Judul iklan maksimal 255 karakter.',
            'url_tujuan.required'    => 'URL tautan tujuan wajib diisi.',
            'url_tujuan.url'         => 'Format URL tujuan tidak valid (gunakan format https://...).',
            'gambar_banner.required' => 'File banner iklan wajib diupload.',
            'gambar_banner.image'    => 'File banner harus berupa gambar.',
            'gambar_banner.mimes'    => 'Format gambar harus JPEG, PNG, JPG, WEBP, atau GIF.',
            'gambar_banner.max'      => 'Ukuran banner maksimal 5MB.',
            'durasi_hari.required'   => 'Durasi penayangan wajib ditentukan.',
            'durasi_hari.integer'    => 'Durasi penayangan harus berupa angka.',
            'durasi_hari.min'        => 'Durasi penayangan minimal 1 hari.',
            'durasi_hari.max'        => 'Durasi penayangan maksimal 3650 hari (10 tahun).',
            'perusahaan_id.exists'   => 'Perusahaan yang dipilih tidak valid.',
        ];
    }
}


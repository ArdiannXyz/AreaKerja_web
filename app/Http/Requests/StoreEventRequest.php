<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']);
    }

    public function rules(): array
    {
        return [
            'title'                  => 'required|string|max:255',
            'status'                 => 'required|string|in:buka,tutup,draft',
            'tgl_mulai'              => 'required|date',
            'tgl_akhir'              => 'required|date|after_or_equal:tgl_mulai',
            'kuota'                  => 'nullable|integer|min:1',
            'image'                  => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
            'content'                => 'nullable|string',
            'jam_mulai'              => 'nullable|string|max:10',
            'jam_akhir'              => 'nullable|string|max:10',
            'lokasi'                 => 'nullable|string',
            'link_form'              => 'nullable|url',
            'penutupan_pendaftaran'  => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'     => 'Judul event wajib diisi.',
            'status.required'    => 'Status event wajib dipilih.',
            'tgl_mulai.required' => 'Tanggal mulai event wajib ditentukan.',
            'tgl_akhir.required' => 'Tanggal akhir event wajib ditentukan.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
            'image.image'        => 'File banner harus berupa gambar.',
            'image.max'          => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}


<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegisterPerusahaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('username') && $this->has('nama_perusahaan')) {
            $this->merge([
                'username' => Str::slug($this->nama_perusahaan) . '_' . rand(100, 999),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'username'           => 'required|string|max:100|unique:users,username',
            'email'              => 'required|email|max:255|unique:users,email',
            'password'           => 'required|string|min:8',
            'role'               => 'required|string',
            'nama_perusahaan'    => 'required|string|max:255',
            'telepon_perusahaan' => ['required', 'regex:/^(?:628|08)[0-9]+$/'],
            'agree_perusahaan'   => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'           => 'Username wajib diisi.',
            'username.unique'             => 'Username / Nama Perusahaan sudah digunakan.',
            'email.required'              => 'Email wajib diisi.',
            'email.email'                 => 'Format email tidak valid.',
            'email.unique'                => 'Email sudah terdaftar.',
            'password.required'           => 'Password wajib diisi.',
            'password.min'                => 'Password minimal 8 karakter.',
            'role.required'               => 'Role wajib diisi.',
            'nama_perusahaan.required'    => 'Nama Perusahaan wajib diisi.',
            'telepon_perusahaan.required' => 'Nomor telepon perusahaan wajib diisi.',
            'telepon_perusahaan.regex'    => 'Nomor telepon harus diawali dengan 628, atau 08.',
            'agree_perusahaan.accepted'   => 'Anda harus menyetujui syarat dan ketentuan.',
        ];
    }
}


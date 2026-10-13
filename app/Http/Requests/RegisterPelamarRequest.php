<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterPelamarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'         => 'required|string|max:100|unique:users,username',
            'email'            => 'required|email|max:255|unique:users,email',
            'password'         => 'required|string|min:8',
            'role'             => 'required|string',
            'telepon_pelamar'  => ['required', 'regex:/^(?:628|08)[0-9]+$/'],
            'agree_pelamar'    => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'        => 'Username wajib diisi.',
            'username.unique'          => 'Username sudah digunakan.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.unique'             => 'Email sudah terdaftar.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 8 karakter.',
            'role.required'            => 'Role wajib diisi.',
            'telepon_pelamar.required' => 'Nomor telepon wajib diisi.',
            'telepon_pelamar.regex'    => 'Nomor telepon harus diawali dengan 628, atau 08.',
            'agree_pelamar.accepted'   => 'Anda harus menyetujui syarat dan ketentuan.',
        ];
    }
}


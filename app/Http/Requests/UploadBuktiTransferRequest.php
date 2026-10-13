<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }

    /**
     * Custom message for validation rules.
     */
    public function messages(): array
    {
        return [
            'bukti.required' => 'Silakan pilih file bukti pembayaran terlebih dahulu.',
            'bukti.file'     => 'File bukti transfer tidak valid.',
            'bukti.mimes'    => 'Format file bukti pembayaran harus berupa JPG, JPEG, PNG, atau PDF.',
            'bukti.max'      => 'Ukuran file bukti pembayaran maksimal 5MB.',
        ];
    }
}


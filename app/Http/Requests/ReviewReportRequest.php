<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $role = strtolower((string) optional($this->user())->role);

        return in_array($role, ['admin', 'super_admin'], true);
    }

    public function rules(): array
    {
        return [
            'action'        => ['required', Rule::in(['dismiss', 'hide', 'warn', 'freeze', 'restore', 'revoke_kandidat', 'unfreeze'])],
            'admin_notes'   => ['nullable', 'string', 'max:1000'],
            'alasan_freeze' => ['required_if:action,freeze', 'nullable', 'string', 'max:255'],
        ];
    }
}

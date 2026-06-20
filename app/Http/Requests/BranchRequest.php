<?php

namespace App\Http\Requests;

use App\Support\IndonesianPhone;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merged = [];

        if ($this->has('is_active')) {
            $merged['is_active'] = $this->boolean('is_active');
        }

        if ($this->filled('phone')) {
            $merged['phone'] = IndonesianPhone::normalize($this->input('phone'));
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama cabang wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
        ];
    }
}

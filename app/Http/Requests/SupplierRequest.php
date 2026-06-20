<?php

namespace App\Http\Requests;

use App\Support\IndonesianPhone;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
        $supplier = $this->route('supplier');

        return [
            'branch_id' => [
                $supplier || ! $this->user()->canSelectBranch() ? 'prohibited' : 'required',
                'exists:branches,id',
            ],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang wajib dipilih.',
            'name.required' => 'Nama supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}

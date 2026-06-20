<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        }
    }

    public function rules(): array
    {
        $supplier = $this->route('supplier');
        $branchId = $supplier?->branch_id ?? $this->input('branch_id');

        return [
            'branch_id' => [$supplier ? 'prohibited' : 'required', 'exists:branches,id'],
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('suppliers', 'code')
                    ->where('branch_id', $branchId)
                    ->ignore($supplier),
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
            'code.required' => 'Kode supplier wajib diisi.',
            'code.unique' => 'Kode supplier sudah digunakan di cabang ini.',
            'name.required' => 'Nama supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ];
    }
}

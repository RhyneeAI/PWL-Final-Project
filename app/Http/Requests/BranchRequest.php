<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
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
        $branch = $this->route('branch');

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('branches', 'code')->ignore($branch),
            ],
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode cabang wajib diisi.',
            'code.unique' => 'Kode cabang sudah digunakan.',
            'name.required' => 'Nama cabang wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
        ];
    }
}

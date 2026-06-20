<?php

namespace App\Http\Requests;

use App\Enums\ProductUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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

        foreach (['buy_price', 'sell_price', 'min_stock'] as $field) {
            if ($this->has($field)) {
                $merged[$field] = $this->normalizeFormattedNumber($this->input($field));
            }
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }

    public function rules(): array
    {
        $user = $this->user();

        return [
            'branch_id' => [
                ! $user->canSelectBranch() ? 'prohibited' : 'required',
                'exists:branches,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($user): void {
                    if ($value && ! $user->hasAccessToBranch((int) $value)) {
                        $fail('Cabang tidak valid atau tidak dapat diakses.');
                    }
                },
            ],
            'category_id' => ['nullable', 'exists:categories,id'],
            'code' => ['prohibited'],
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products', 'barcode')->ignore($product),
            ],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', Rule::enum(ProductUnit::class)],
            'buy_price' => ['required', 'numeric', 'min:0'],
            'sell_price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang wajib dipilih.',
            'branch_id.prohibited' => 'Cabang tidak dapat diubah.',
            'barcode.unique' => 'Barcode sudah digunakan.',
            'name.required' => 'Nama produk wajib diisi.',
            'unit.required' => 'Satuan wajib dipilih.',
            'buy_price.required' => 'Harga beli wajib diisi.',
            'sell_price.required' => 'Harga jual wajib diisi.',
            'min_stock.required' => 'Stok minimum wajib diisi.',
        ];
    }

    private function normalizeFormattedNumber(mixed $value): string
    {
        return (string) (int) preg_replace('/\D/', '', (string) $value);
    }
}

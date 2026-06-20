<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role->canManageStock() ?? false;
    }

    protected function prepareForValidation(): void
    {
        $merged = [];

        if ($this->has('items') && is_array($this->input('items'))) {
            $items = [];

            foreach ($this->input('items') as $item) {
                if (! is_array($item)) {
                    continue;
                }

                if (array_key_exists('buy_price', $item)) {
                    $item['buy_price'] = $this->normalizeFormattedNumber($item['buy_price']);
                }

                if (array_key_exists('quantity', $item)) {
                    $item['quantity'] = $this->normalizeFormattedNumber($item['quantity']);
                }

                $items[] = $item;
            }

            $merged['items'] = $items;
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
                $user->canSelectBranch() ? 'required' : 'prohibited',
                'exists:branches,id',
            ],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'mutation_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.buy_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            /** @var User $user */
            $user = $this->user();
            $branchId = $this->resolvedBranchId();

            if (! $branchId) {
                $validator->errors()->add('branch_id', 'Cabang pengguna tidak ditemukan.');

                return;
            }

            if (! $user->hasAccessToBranch($branchId)) {
                $validator->errors()->add('branch_id', 'Cabang tidak valid atau tidak dapat diakses.');

                return;
            }

            $supplier = Supplier::query()->find($this->input('supplier_id'));

            if ($supplier && $supplier->branch_id !== $branchId) {
                $validator->errors()->add('supplier_id', 'Supplier tidak termasuk cabang yang dipilih.');
            }

            if ($supplier && ! $supplier->is_active) {
                $validator->errors()->add('supplier_id', 'Supplier tidak aktif.');
            }

            $productIds = collect($this->input('items', []))->pluck('product_id')->filter()->all();

            if ($productIds === []) {
                return;
            }

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            foreach ($this->input('items', []) as $index => $item) {
                $product = $products->get($item['product_id'] ?? null);

                if (! $product) {
                    continue;
                }

                if ($product->branch_id !== $branchId) {
                    $validator->errors()->add("items.{$index}.product_id", 'Produk tidak termasuk cabang yang dipilih.');
                }

                if (! $product->is_active) {
                    $validator->errors()->add("items.{$index}.product_id", 'Produk tidak aktif.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'mutation_date.required' => 'Tanggal transaksi wajib diisi.',
            'mutation_date.date' => 'Format tanggal transaksi tidak valid.',
            'items.required' => 'Minimal satu produk wajib ditambahkan.',
            'items.min' => 'Minimal satu produk wajib ditambahkan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.product_id.distinct' => 'Produk tidak boleh duplikat.',
            'items.*.quantity.required' => 'Jumlah wajib diisi.',
            'items.*.quantity.min' => 'Jumlah minimal 1.',
            'items.*.buy_price.required' => 'Harga beli wajib diisi.',
        ];
    }

    public function resolvedBranchId(): int
    {
        /** @var User $user */
        $user = $this->user();

        if ($user->canSelectBranch()) {
            return (int) $this->input('branch_id');
        }

        return (int) $user->branches()->value('branches.id');
    }

    /**
     * @return list<array{product_id: int, quantity: int, buy_price: float}>
     */
    public function normalizedItems(): array
    {
        return collect($this->input('items', []))
            ->map(fn (array $item) => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'buy_price' => (float) $item['buy_price'],
            ])
            ->values()
            ->all();
    }

    private function normalizeFormattedNumber(mixed $value): string
    {
        return (string) (int) preg_replace('/\D/', '', (string) $value);
    }
}

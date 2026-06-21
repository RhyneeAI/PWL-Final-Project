<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Validator;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->role->canManageTransactions() ?? false;
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

                if (array_key_exists('quantity', $item)) {
                    $item['quantity'] = $this->normalizeFormattedNumber($item['quantity']);
                }

                $items[] = $item;
            }

            $merged['items'] = $items;
        }

        if ($this->has('discount')) {
            $merged['discount'] = $this->normalizeFormattedNumber($this->input('discount'));
        }

        if ($this->has('paid_amount')) {
            $merged['paid_amount'] = $this->normalizeFormattedNumber($this->input('paid_amount'));
        }

        if ($merged !== []) {
            $this->merge($merged);
        }
    }

    public function rules(): array
    {
        $user = $this->user();
        $minDate = now()->subDays(3)->format('Y-m-d');
        $maxDate = now()->format('Y-m-d');

        return [
            'branch_id' => [
                $user->canSelectBranch() ? 'required' : 'prohibited',
                'exists:branches,id',
            ],
            'transaction_date' => ['required', 'date', "after_or_equal:{$minDate}", "before_or_equal:{$maxDate}"],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,transfer,qris'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
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

                $requestedQty = (int) ($item['quantity'] ?? 0);
                if ($product->stock < $requestedQty) {
                    $validator->errors()->add("items.{$index}.quantity", "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.");
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Cabang wajib dipilih.',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.after_or_equal' => 'Tanggal transaksi maksimal H-3 dari hari ini.',
            'transaction_date.before_or_equal' => 'Tanggal transaksi tidak boleh melebihi hari ini.',
            'paid_amount.required' => 'Total bayar wajib diisi.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'items.required' => 'Minimal satu produk wajib ditambahkan.',
            'items.min' => 'Minimal satu produk wajib ditambahkan.',
            'items.*.product_id.required' => 'Produk wajib dipilih.',
            'items.*.product_id.distinct' => 'Produk tidak boleh duplikat.',
            'items.*.quantity.required' => 'Jumlah wajib diisi.',
            'items.*.quantity.min' => 'Jumlah minimal 1.',
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
     * @return list<array{product_id: int, quantity: int}>
     */
    public function normalizedItems(): array
    {
        return collect($this->input('items', []))
            ->map(fn (array $item) => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
            ])
            ->values()
            ->all();
    }

    private function normalizeFormattedNumber(mixed $value): string
    {
        return (string) (int) preg_replace('/\D/', '', (string) $value);
    }
}

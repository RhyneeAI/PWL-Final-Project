<?php

namespace App\Services;

use App\Enums\StockMutationType;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockOutService
{
    /**
     * @param  list<array{product_id: int, quantity: int}>  $items
     */
    public function store(
        int $branchId,
        array $items,
        User $actor,
        \DateTimeInterface|string $mutationDate,
        ?string $notes = null,
    ): string {
        return DB::transaction(function () use ($branchId, $items, $actor, $mutationDate, $notes) {
            $referenceCode = StockMutation::generateNextReferenceCodeForOut($branchId);
            $mutationDate = \Illuminate\Support\Carbon::parse($mutationDate);

            foreach ($items as $item) {
                /** @var Product $product */
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->branch_id !== $branchId) {
                    throw new \InvalidArgumentException('Produk tidak sesuai cabang.');
                }

                $quantity = $item['quantity'];

                if ($product->stock < $quantity) {
                    throw new \InvalidArgumentException("Stok {$product->name} tidak mencukupi.");
                }

                $before = $product->stock;
                $after = $before - $quantity;

                StockMutation::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'user_id' => $actor->id,
                    'reference_code' => $referenceCode,
                    'type' => StockMutationType::AdjustOut,
                    'quantity_before' => $before,
                    'quantity_change' => -$quantity,
                    'quantity_after' => $after,
                    'notes' => $notes,
                    'mutation_date' => $mutationDate,
                ]);

                $product->update([
                    'stock' => $after,
                ]);
            }

            return $referenceCode;
        });
    }
}

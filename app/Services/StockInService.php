<?php

namespace App\Services;

use App\Enums\StockMutationType;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockInService
{
    /**
     * @param  list<array{product_id: int, quantity: int, buy_price: float}>  $items
     */
    public function store(
        int $branchId,
        int $supplierId,
        array $items,
        User $actor,
        \DateTimeInterface|string $mutationDate,
        ?string $notes = null,
    ): string {
        return DB::transaction(function () use ($branchId, $supplierId, $items, $actor, $mutationDate, $notes) {
            $referenceCode = StockMutation::generateNextReferenceCode($branchId);
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
                $before = $product->stock;
                $after = $before + $quantity;

                StockMutation::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'user_id' => $actor->id,
                    'supplier_id' => $supplierId,
                    'reference_code' => $referenceCode,
                    'type' => StockMutationType::AdjustIn,
                    'quantity_before' => $before,
                    'quantity_change' => $quantity,
                    'quantity_after' => $after,
                    'buy_price' => $item['buy_price'],
                    'notes' => $notes,
                    'mutation_date' => $mutationDate,
                ]);

                $product->update([
                    'stock' => $after,
                    'buy_price' => $item['buy_price'],
                ]);
            }

            return $referenceCode;
        });
    }
}

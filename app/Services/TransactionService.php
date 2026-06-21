<?php

namespace App\Services;

use App\Enums\StockMutationType;
use App\Enums\TransactionStatus;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * @param  list<array{product_id: int, quantity: int}>  $items
     */
    public function store(
        int $branchId,
        array $items,
        User $actor,
        \DateTimeInterface|string $transactionDate,
        string $paymentMethod,
        float $paidAmount,
        float $discount = 0,
        ?string $notes = null,
    ): Transaction {
        return DB::transaction(function () use (
            $branchId, $items, $actor, $transactionDate,
            $paymentMethod, $paidAmount,
            $discount, $notes
        ) {
            $transactionDate = \Illuminate\Support\Carbon::parse($transactionDate);
            $transactionItems = [];
            $subtotal = 0;

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

                $price = (float) $product->sell_price;
                $itemSubtotal = $price * $quantity;
                $subtotal += $itemSubtotal;

                $transactionItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'item_subtotal' => $itemSubtotal,
                ];
            }

            $total = $subtotal - $discount;
            $changeAmount = max(0, $paidAmount - $total);

            $transaction = Transaction::create([
                'branch_id' => $branchId,
                'user_id' => $actor->id,
                'code' => Transaction::generateNextTransactionCode($branchId),
                'status' => TransactionStatus::Completed,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => 0,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'notes' => $notes,
                'transaction_date' => $transactionDate,
            ]);

            foreach ($transactionItems as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $itemSubtotal = $item['item_subtotal'];

                $before = $product->stock;
                $after = $before - $quantity;

                $transaction->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $price,
                    'quantity' => $quantity,
                    'discount' => 0,
                    'subtotal' => $itemSubtotal,
                ]);

                StockMutation::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'user_id' => $actor->id,
                    'transaction_id' => $transaction->id,
                    'reference_code' => $transaction->code,
                    'type' => StockMutationType::SalesOut,
                    'quantity_before' => $before,
                    'quantity_change' => -$quantity,
                    'quantity_after' => $after,
                    'mutation_date' => $transactionDate,
                ]);

                $product->update(['stock' => $after]);
            }

            return $transaction;
        });
    }
}

<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Enums\StockMutationType;
use App\Enums\TransactionStatus;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {
            $cashiers = User::where('role', 'cashier')
                ->whereHas('branches', fn ($q) => $q->where('branches.id', $branch->id))
                ->get();

            if ($cashiers->isEmpty()) {
                continue;
            }

            $products = Product::where('branch_id', $branch->id)->get();
            if ($products->isEmpty()) {
                continue;
            }

            $transactions = [
                [
                    'days_ago' => 6,
                    'items'    => [
                        ['product_idx' => 0, 'qty' => 3],
                        ['product_idx' => 2, 'qty' => 2],
                    ],
                    'payment'   => PaymentMethod::Cash,
                    'status'    => TransactionStatus::Completed,
                ],
                [
                    'days_ago' => 5,
                    'items'    => [
                        ['product_idx' => 1, 'qty' => 5],
                        ['product_idx' => 4, 'qty' => 10],
                    ],
                    'payment'   => PaymentMethod::Qris,
                    'status'    => TransactionStatus::Completed,
                ],
                [
                    'days_ago' => 4,
                    'items'    => [
                        ['product_idx' => 7, 'qty' => 2],
                        ['product_idx' => 9, 'qty' => 6],
                        ['product_idx' => 11, 'qty' => 4],
                    ],
                    'discount'  => 5000,
                    'payment'   => PaymentMethod::Transfer,
                    'status'    => TransactionStatus::Completed,
                ],
                [
                    'days_ago' => 3,
                    'items'    => [
                        ['product_idx' => 13, 'qty' => 1],
                        ['product_idx' => 14, 'qty' => 2],
                    ],
                    'payment'   => PaymentMethod::Cash,
                    'status'    => TransactionStatus::Pending,
                ],
                [
                    'days_ago' => 2,
                    'items'    => [
                        ['product_idx' => 18, 'qty' => 3],
                        ['product_idx' => 20, 'qty' => 2],
                        ['product_idx' => 23, 'qty' => 1],
                    ],
                    'payment'   => PaymentMethod::Cash,
                    'status'    => TransactionStatus::Completed,
                ],
                [
                    'days_ago' => 1,
                    'items'    => [
                        ['product_idx' => 3, 'qty' => 4],
                        ['product_idx' => 8, 'qty' => 2],
                    ],
                    'payment'   => PaymentMethod::Qris,
                    'status'    => TransactionStatus::Cancelled,
                ],
                [
                    'days_ago' => 0,
                    'items'    => [
                        ['product_idx' => 0, 'qty' => 2],
                        ['product_idx' => 5, 'qty' => 3],
                        ['product_idx' => 10, 'qty' => 5],
                    ],
                    'payment'   => PaymentMethod::Cash,
                    'status'    => TransactionStatus::Completed,
                ],
            ];

            foreach ($transactions as $trxData) {
                $cashier = $cashiers->random();
                $date = now()->subDays($trxData['days_ago'])->setHour(rand(8, 17))->setMinute(rand(0, 59));

                $discount = $trxData['discount'] ?? 0;
                $tax = 0;
                $subtotal = 0;
                $items = [];

                foreach ($trxData['items'] as $itemData) {
                    if ($itemData['product_idx'] >= $products->count()) {
                        continue;
                    }

                    $product = $products[$itemData['product_idx']];
                    $itemSubtotal = $product->sell_price * $itemData['qty'];
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'product_id'    => $product->id,
                        'product_name'  => $product->name,
                        'product_price' => $product->sell_price,
                        'quantity'      => $itemData['qty'],
                        'discount'      => 0,
                        'subtotal'      => $itemSubtotal,
                    ];
                }

                if (empty($items)) {
                    continue;
                }

                $total = $subtotal - $discount + $tax;
                $paidAmount = $trxData['status'] === TransactionStatus::Completed
                    ? $total + rand(0, 5000)
                    : 0;
                $changeAmount = $paidAmount > 0 ? $paidAmount - $total : 0;

                $code = $branch->transactionCodePrefix() . '-' . $date->format('Ymd') . '-'
                    . str_pad((string) (Transaction::withTrashed()->where('branch_id', $branch->id)->count() + 1), 4, '0', STR_PAD_LEFT);

                $transaction = Transaction::create([
                    'branch_id'       => $branch->id,
                    'user_id'         => $cashier->id,
                    'code'            => $code,
                    'status'          => $trxData['status'],
                    'subtotal'        => $subtotal,
                    'discount'        => $discount,
                    'tax'             => $tax,
                    'total'           => $total,
                    'paid_amount'     => $paidAmount,
                    'change_amount'   => $changeAmount,
                    'payment_method'  => $trxData['payment'],
                    'notes'           => null,
                    'transaction_date' => $date,
                    'created_at'      => $date,
                    'updated_at'      => $date,
                ]);

                foreach ($items as $itemData) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $itemData['product_id'],
                        'product_name'   => $itemData['product_name'],
                        'product_price'  => $itemData['product_price'],
                        'quantity'       => $itemData['quantity'],
                        'discount'       => $itemData['discount'],
                        'subtotal'       => $itemData['subtotal'],
                    ]);

                    if ($trxData['status'] === TransactionStatus::Completed) {
                        $product = Product::find($itemData['product_id']);
                        if ($product) {
                            $before = $product->stock;
                            $after = max(0, $before - $itemData['quantity']);

                            StockMutation::create([
                                'branch_id'       => $branch->id,
                                'product_id'      => $product->id,
                                'user_id'         => $cashier->id,
                                'transaction_id'  => $transaction->id,
                                'reference_code'  => $code,
                                'type'            => StockMutationType::SalesOut,
                                'quantity_before' => $before,
                                'quantity_change' => -$itemData['quantity'],
                                'quantity_after'  => $after,
                                'buy_price'       => $product->buy_price,
                                'notes'           => 'Penjualan ' . $code,
                                'mutation_date'   => $date,
                            ]);

                            $product->update(['stock' => $after]);
                        }
                    }
                }
            }
        }
    }
}

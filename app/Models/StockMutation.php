<?php

namespace App\Models;

use App\Enums\StockMutationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $fillable = [
        'branch_id', 'product_id', 'user_id', 'transaction_id', 'supplier_id',
        'reference_code', 'type', 'quantity_before',
        'quantity_change', 'quantity_after', 'buy_price', 'notes', 'mutation_date',
    ];

    protected function casts(): array
    {
        return [
            'type'          => StockMutationType::class,
            'mutation_date' => 'datetime',
            'buy_price'     => 'decimal:2',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public static function generateNextReferenceCode(int $branchId): string
    {
        $branch = Branch::findOrFail($branchId);
        $prefix = $branch->stockInCodePrefix();
        $date = now()->format('Ymd');

        $lastNumber = static::query()
            ->where('branch_id', $branchId)
            ->where('reference_code', 'like', $prefix . '-' . $date . '-%')
            ->pluck('reference_code')
            ->map(fn (string $code) => (int) substr($code, strlen($prefix) + 10))
            ->max() ?? 0;

        return $prefix . '-' . $date . '-' . str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
    }

    public static function generateNextReferenceCodeForOut(int $branchId): string
    {
        $branch = Branch::findOrFail($branchId);
        $prefix = $branch->stockOutCodePrefix();
        $date = now()->format('Ymd');

        $lastNumber = static::query()
            ->where('branch_id', $branchId)
            ->where('reference_code', 'like', $prefix . '-' . $date . '-%')
            ->pluck('reference_code')
            ->map(fn (string $code) => (int) substr($code, strlen($prefix) + 10))
            ->max() ?? 0;

        return $prefix . '-' . $date . '-' . str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
}

    public function subtotal(): float
    {
        return (float) $this->buy_price * abs($this->quantity_change);
    }

    public function signedQuantityLabel(): string
    {
        $qty = abs($this->quantity_change);
        $prefix = $this->quantity_change >= 0 ? '+' : '−';

        return $prefix . number_format($qty, 0, ',', '.');
    }

    public function isStockIncrease(): bool
    {
        return $this->quantity_change > 0;
    }
}

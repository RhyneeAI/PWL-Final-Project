<?php

namespace App\Models;

use App\Enums\ProductUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id', 'category_id', 'code', 'barcode',
        'name', 'unit', 'buy_price', 'sell_price',
        'stock', 'min_stock', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'buy_price'  => 'decimal:2',
            'sell_price' => 'decimal:2',
            'unit'       => ProductUnit::class,
            'is_active'  => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public static function generateNextCode(int $branchId): string
    {
        $branch = Branch::findOrFail($branchId);
        $prefix = $branch->productCodePrefix();

        $lastNumber = static::withTrashed()
            ->where('branch_id', $branchId)
            ->where('code', 'like', $prefix . '-%')
            ->pluck('code')
            ->map(fn (string $code) => (int) substr($code, strlen($prefix) + 1))
            ->max() ?? 0;

        return $prefix . '-' . str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }
}

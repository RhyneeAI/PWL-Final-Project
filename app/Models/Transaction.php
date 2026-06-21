<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id', 'user_id', 'code', 'status',
        'subtotal', 'discount', 'tax', 'total',
        'paid_amount', 'change_amount', 'payment_method',
        'notes', 'transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'status'           => TransactionStatus::class,
            'payment_method'   => PaymentMethod::class,
            'subtotal'         => 'decimal:2',
            'discount'         => 'decimal:2',
            'tax'              => 'decimal:2',
            'total'            => 'decimal:2',
            'paid_amount'      => 'decimal:2',
            'change_amount'    => 'decimal:2',
            'transaction_date' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public static function generateNextTransactionCode(int $branchId): string
    {
        $branch = Branch::findOrFail($branchId);
        $prefix = $branch->transactionCodePrefix();

        $lastNumber = static::query()
            ->where('branch_id', $branchId)
            ->where('code', 'like', $prefix . '-%')
            ->pluck('code')
            ->map(fn (string $code) => (int) substr($code, strlen($prefix) + 1))
            ->max() ?? 0;

        return $prefix . '-' . str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }
}

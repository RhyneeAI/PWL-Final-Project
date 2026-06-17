<?php

namespace App\Models;

use App\Enums\StockMutationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutation extends Model
{
    protected $fillable = [
        'branch_id', 'product_id', 'user_id', 'transaction_id',
        'reference_code', 'type', 'quantity_before',
        'quantity_change', 'quantity_after', 'notes', 'mutation_date',
    ];

    protected function casts(): array
    {
        return [
            'type'          => StockMutationType::class,
            'mutation_date' => 'datetime',
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
}

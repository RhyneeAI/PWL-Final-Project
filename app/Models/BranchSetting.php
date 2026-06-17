<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchSetting extends Model
{
    protected $fillable = [
        'branch_id', 'product_prefix', 'transaction_prefix',
        'tax_enabled', 'tax_rate', 'discount_enabled', 'currency_symbol',
    ];

    protected function casts(): array
    {
        return [
            'tax_enabled'      => 'boolean',
            'tax_rate'         => 'decimal:2',
            'discount_enabled' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

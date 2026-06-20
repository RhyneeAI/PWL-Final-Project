<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'code',
        'name',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public static function generateNextCode(int $branchId): string
    {
        $branch = Branch::findOrFail($branchId);
        $prefix = $branch->supplierCodePrefix();

        $lastNumber = static::withTrashed()
            ->where('branch_id', $branchId)
            ->where('code', 'like', $prefix . '-%')
            ->pluck('code')
            ->map(fn (string $code) => (int) substr($code, strlen($prefix) + 1))
            ->max() ?? 0;

        return $prefix . '-' . str_pad((string) ($lastNumber + 1), 3, '0', STR_PAD_LEFT);
    }
}

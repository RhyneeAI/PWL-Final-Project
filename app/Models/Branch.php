<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branch');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(BranchSetting::class);
    }

    public static function generateNextCode(): string
    {
        $lastCode = static::withTrashed()
            ->where('code', 'like', 'BR-%')
            ->orderByRaw('CAST(SUBSTRING(code, 4) AS UNSIGNED) DESC')
            ->value('code');

        $nextNumber = $lastCode ? ((int) substr($lastCode, 3)) + 1 : 1;

        return 'BR-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function supplierCodePrefix(): string
    {
        $initials = collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');

        return 'SUP' . $initials;
    }

    public function productCodePrefix(): string
    {
        $initials = collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');

        return 'PRD' . $initials;
    }
}

<?php

namespace App\Enums;

enum StockMutationType: string
{
    case AdjustIn  = 'ADJUST_IN';
    case AdjustOut = 'ADJUST_OUT';
    case Opname    = 'OPNAME';
    case SalesOut  = 'SALES_OUT';

    public function label(): string
    {
        return match($this) {
            self::AdjustIn  => 'Stok Masuk',
            self::AdjustOut => 'Stok Keluar',
            self::Opname    => 'Stock Opname',
            self::SalesOut  => 'Penjualan',
        };
    }

    /**
     * Apakah mutasi ini menambah stok (true) atau mengurangi (false)?
     * Opname bisa keduanya, bergantung quantity_change-nya.
     */
    public function isAddition(): bool
    {
        return $this === self::AdjustIn;
    }

    public function isDeduction(): bool
    {
        return in_array($this, [self::AdjustOut, self::SalesOut]);
    }
}

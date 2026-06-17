<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Menunggu',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::Pending   => 'amber',
            self::Completed => 'emerald',
            self::Cancelled => 'red',
        };
    }
}

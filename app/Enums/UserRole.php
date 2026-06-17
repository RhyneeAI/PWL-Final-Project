<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner    = 'owner';
    case Admin    = 'admin';
    case Cashier  = 'cashier';

    public function label(): string
    {
        return match($this) {
            self::Owner   => 'Owner',
            self::Admin   => 'Admin',
            self::Cashier => 'Kasir',
        };
    }

    public function canAccessAllBranches(): bool
    {
        return $this === self::Owner;
    }

    public function canManageUsers(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canPrintReport(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }

    public function canManageStock(): bool
    {
        return in_array($this, [self::Owner, self::Admin]);
    }
}

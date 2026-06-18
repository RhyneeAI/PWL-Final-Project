<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner     = 'owner';
    case Manager   = 'manager';
    case Cashier   = 'cashier';
    case Warehouse = 'warehouse';

    public function label(): string
    {
        return match ($this) {
            self::Owner     => 'Owner',
            self::Manager   => 'Manager',
            self::Cashier   => 'Kasir',
            self::Warehouse => 'Pegawai Gudang',
        };
    }

    public function canAccessAllBranches(): bool
    {
        return $this === self::Owner;
    }

    public function canManageBranches(): bool
    {
        return $this === self::Owner;
    }

    public function canManageUsers(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }

    public function canManageCategories(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }

    public function canManageProducts(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }

    public function canViewProducts(): bool
    {
        return in_array($this, [self::Owner, self::Manager, self::Cashier, self::Warehouse]);
    }

    public function canManageTransactions(): bool
    {
        return in_array($this, [self::Owner, self::Manager, self::Cashier]);
    }

    public function canViewTransactions(): bool
    {
        return in_array($this, [self::Owner, self::Manager, self::Cashier]);
    }

    public function canManageStock(): bool
    {
        return in_array($this, [self::Owner, self::Manager, self::Warehouse]);
    }

    public function canPrintReport(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }

    public function canManageSettings(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }
}

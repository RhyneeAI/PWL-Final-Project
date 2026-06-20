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

    public function canManageSuppliers(): bool
    {
        return in_array($this, [self::Owner, self::Manager]);
    }

    public function canViewSuppliers(): bool
    {
        return in_array($this, [self::Owner, self::Manager, self::Warehouse]);
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

    /**
     * Role yang boleh ditetapkan oleh pengguna dengan role tertentu.
     *
     * @return list<self>
     */
    public static function assignableBy(self $actor): array
    {
        return match ($actor) {
            self::Owner => self::cases(),
            self::Manager => [self::Manager, self::Cashier, self::Warehouse],
            default => [],
        };
    }

    public static function assignableValuesBy(self $actor): array
    {
        return array_map(fn (self $role) => $role->value, self::assignableBy($actor));
    }

    public function canBeAssignedBy(self $actor): bool
    {
        return in_array($this, self::assignableBy($actor), true);
    }

    public function hierarchyLevel(): int
    {
        return match ($this) {
            self::Owner => 4,
            self::Manager => 3,
            self::Cashier, self::Warehouse => 2,
        };
    }

    public function isAbove(self $other): bool
    {
        return $this->hierarchyLevel() > $other->hierarchyLevel();
    }

    public static function canManageAccount(self $actor, self $target): bool
    {
        return $actor->hierarchyLevel() >= $target->hierarchyLevel();
    }
}

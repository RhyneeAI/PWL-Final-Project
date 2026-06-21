<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner     = 'owner';
    case Supervisor   = 'supervisor';
    case Cashier   = 'cashier';
    case Warehouse = 'warehouse';

    public function label(): string
    {
        return match ($this) {
            self::Owner     => 'Owner',
            self::Supervisor   => 'Supervisor',
            self::Cashier   => 'Kasir',
            self::Warehouse => 'Pegawai Gudang',
        };
    }

    public function listOrder(): int
    {
        return match ($this) {
            self::Owner => 0,
            self::Supervisor => 1,
            self::Warehouse => 2,
            self::Cashier => 3,
        };
    }

    /**
     * @return list<self>
     */
    public static function displayOrder(): array
    {
        $roles = self::cases();
        usort($roles, fn (self $a, self $b) => $a->listOrder() <=> $b->listOrder());

        return $roles;
    }

    /**
     * @param  list<self>  $roles
     * @return list<self>
     */
    public static function sortForDisplay(array $roles): array
    {
        usort($roles, fn (self $a, self $b) => $a->listOrder() <=> $b->listOrder());

        return $roles;
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
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    public function canManageCategories(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    public function canManageProducts(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    public function canViewProducts(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor, self::Cashier, self::Warehouse]);
    }

    public function canManageSuppliers(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    public function canViewSuppliers(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor, self::Warehouse]);
    }

    public function canManageTransactions(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor, self::Cashier]);
    }

    public function canViewTransactions(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor, self::Cashier]);
    }

    public function canManageStock(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor, self::Warehouse]);
    }

    public function canPrintReport(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    public function canManageSettings(): bool
    {
        return in_array($this, [self::Owner, self::Supervisor]);
    }

    /**
     * Role yang boleh ditetapkan oleh pengguna dengan role tertentu.
     *
     * @return list<self>
     */
    public static function assignableBy(self $actor): array
    {
        $roles = match ($actor) {
            self::Owner => self::cases(),
            self::Supervisor => [self::Supervisor, self::Cashier, self::Warehouse],
            default => [],
        };

        return self::sortForDisplay($roles);
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
            self::Supervisor => 3,
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

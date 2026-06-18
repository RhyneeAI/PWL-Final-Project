<?php

use App\Enums\UserRole;

// ─── Label ────────────────────────────────────────────────────────────────────

it('owner memiliki label yang benar', function () {
    expect(UserRole::Owner->label())->toBe('Owner');
});

it('manager memiliki label Manager', function () {
    expect(UserRole::Manager->label())->toBe('Manager');
});

it('cashier memiliki label yang benar', function () {
    expect(UserRole::Cashier->label())->toBe('Kasir');
});

it('warehouse memiliki label Pegawai Gudang', function () {
    expect(UserRole::Warehouse->label())->toBe('Pegawai Gudang');
});

// ─── canAccessAllBranches ─────────────────────────────────────────────────────

it('hanya owner yang dapat mengakses semua cabang', function () {
    expect(UserRole::Owner->canAccessAllBranches())->toBeTrue();
    expect(UserRole::Manager->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Cashier->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Warehouse->canAccessAllBranches())->toBeFalse();
});

// ─── canManageBranches ────────────────────────────────────────────────────────

it('hanya owner yang dapat mengelola cabang', function () {
    expect(UserRole::Owner->canManageBranches())->toBeTrue();
    expect(UserRole::Manager->canManageBranches())->toBeFalse();
    expect(UserRole::Cashier->canManageBranches())->toBeFalse();
});

// ─── canManageUsers ───────────────────────────────────────────────────────────

it('owner dan manager dapat mengelola user', function () {
    expect(UserRole::Owner->canManageUsers())->toBeTrue();
    expect(UserRole::Manager->canManageUsers())->toBeTrue();
    expect(UserRole::Cashier->canManageUsers())->toBeFalse();
    expect(UserRole::Warehouse->canManageUsers())->toBeFalse();
});

// ─── canManageProducts / canViewProducts ────────────────────────────────────────

it('kasir dan gudang dapat melihat produk tanpa kelola', function () {
    expect(UserRole::Cashier->canViewProducts())->toBeTrue();
    expect(UserRole::Cashier->canManageProducts())->toBeFalse();
    expect(UserRole::Warehouse->canViewProducts())->toBeTrue();
    expect(UserRole::Warehouse->canManageProducts())->toBeFalse();
});

it('owner dan manager dapat melihat dan kelola produk', function () {
    expect(UserRole::Owner->canViewProducts())->toBeTrue();
    expect(UserRole::Owner->canManageProducts())->toBeTrue();
    expect(UserRole::Manager->canViewProducts())->toBeTrue();
    expect(UserRole::Manager->canManageProducts())->toBeTrue();
});

it('gudang dapat melihat supplier tanpa kelola', function () {
    expect(UserRole::Warehouse->canViewSuppliers())->toBeTrue();
    expect(UserRole::Warehouse->canManageSuppliers())->toBeFalse();
});

it('kasir tidak dapat akses supplier', function () {
    expect(UserRole::Cashier->canViewSuppliers())->toBeFalse();
    expect(UserRole::Cashier->canManageSuppliers())->toBeFalse();
});

it('owner dan manager dapat kelola supplier', function () {
    expect(UserRole::Owner->canManageSuppliers())->toBeTrue();
    expect(UserRole::Manager->canManageSuppliers())->toBeTrue();
});

// ─── canViewTransactions ──────────────────────────────────────────────────────

it('kasir dapat mengelola dan melihat transaksi', function () {
    expect(UserRole::Cashier->canManageTransactions())->toBeTrue();
    expect(UserRole::Cashier->canViewTransactions())->toBeTrue();
});

it('pegawai gudang tidak dapat akses transaksi', function () {
    expect(UserRole::Warehouse->canViewTransactions())->toBeFalse();
    expect(UserRole::Warehouse->canManageTransactions())->toBeFalse();
});

// ─── canManageStock ───────────────────────────────────────────────────────────

it('pegawai gudang dapat mengelola stok', function () {
    expect(UserRole::Warehouse->canManageStock())->toBeTrue();
    expect(UserRole::Cashier->canManageStock())->toBeFalse();
});

// ─── canPrintReport ───────────────────────────────────────────────────────────

it('hanya owner dan manager yang dapat mencetak laporan', function () {
    expect(UserRole::Owner->canPrintReport())->toBeTrue();
    expect(UserRole::Manager->canPrintReport())->toBeTrue();
    expect(UserRole::Cashier->canPrintReport())->toBeFalse();
    expect(UserRole::Warehouse->canPrintReport())->toBeFalse();
});

// ─── canManageSettings ────────────────────────────────────────────────────────

it('hanya owner dan manager yang dapat mengelola pengaturan', function () {
    expect(UserRole::Owner->canManageSettings())->toBeTrue();
    expect(UserRole::Manager->canManageSettings())->toBeTrue();
    expect(UserRole::Cashier->canManageSettings())->toBeFalse();
});

<?php

use App\Enums\UserRole;

// ─── Label ────────────────────────────────────────────────────────────────────

it('owner memiliki label yang benar', function () {
    expect(UserRole::Owner->label())->toBe('Owner');
});

it('admin memiliki label Manajer Toko', function () {
    expect(UserRole::Admin->label())->toBe('Manajer Toko');
});

it('supervisor memiliki label yang benar', function () {
    expect(UserRole::Supervisor->label())->toBe('Supervisor');
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
    expect(UserRole::Admin->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Supervisor->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Cashier->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Warehouse->canAccessAllBranches())->toBeFalse();
});

// ─── canManageBranches ────────────────────────────────────────────────────────

it('hanya owner yang dapat mengelola cabang', function () {
    expect(UserRole::Owner->canManageBranches())->toBeTrue();
    expect(UserRole::Admin->canManageBranches())->toBeFalse();
    expect(UserRole::Supervisor->canManageBranches())->toBeFalse();
});

// ─── canManageUsers ───────────────────────────────────────────────────────────

it('owner dan manajer toko dapat mengelola user', function () {
    expect(UserRole::Owner->canManageUsers())->toBeTrue();
    expect(UserRole::Admin->canManageUsers())->toBeTrue();
    expect(UserRole::Supervisor->canManageUsers())->toBeFalse();
    expect(UserRole::Cashier->canManageUsers())->toBeFalse();
    expect(UserRole::Warehouse->canManageUsers())->toBeFalse();
});

// ─── canViewTransactions ──────────────────────────────────────────────────────

it('supervisor dapat melihat transaksi tanpa mengelola', function () {
    expect(UserRole::Supervisor->canViewTransactions())->toBeTrue();
    expect(UserRole::Supervisor->canManageTransactions())->toBeFalse();
});

it('kasir dapat mengelola transaksi', function () {
    expect(UserRole::Cashier->canManageTransactions())->toBeTrue();
    expect(UserRole::Cashier->canViewTransactions())->toBeTrue();
});

// ─── canManageStock ───────────────────────────────────────────────────────────

it('pegawai gudang dapat mengelola stok', function () {
    expect(UserRole::Warehouse->canManageStock())->toBeTrue();
    expect(UserRole::Warehouse->canManageTransactions())->toBeFalse();
    expect(UserRole::Cashier->canManageStock())->toBeFalse();
});

// ─── canPrintReport ───────────────────────────────────────────────────────────

it('supervisor dapat mencetak laporan', function () {
    expect(UserRole::Supervisor->canPrintReport())->toBeTrue();
    expect(UserRole::Cashier->canPrintReport())->toBeFalse();
    expect(UserRole::Warehouse->canPrintReport())->toBeFalse();
});

// ─── canManageSettings ────────────────────────────────────────────────────────

it('hanya owner dan manajer toko yang dapat mengelola pengaturan', function () {
    expect(UserRole::Owner->canManageSettings())->toBeTrue();
    expect(UserRole::Admin->canManageSettings())->toBeTrue();
    expect(UserRole::Supervisor->canManageSettings())->toBeFalse();
});

<?php

use App\Enums\UserRole;

// ─── Label ────────────────────────────────────────────────────────────────────

it('owner memiliki label yang benar', function () {
    expect(UserRole::Owner->label())->toBe('Owner');
});

it('admin memiliki label yang benar', function () {
    expect(UserRole::Admin->label())->toBe('Admin');
});

it('cashier memiliki label yang benar', function () {
    expect(UserRole::Cashier->label())->toBe('Kasir');
});

// ─── canAccessAllBranches ─────────────────────────────────────────────────────

it('hanya owner yang dapat mengakses semua cabang', function () {
    expect(UserRole::Owner->canAccessAllBranches())->toBeTrue();
    expect(UserRole::Admin->canAccessAllBranches())->toBeFalse();
    expect(UserRole::Cashier->canAccessAllBranches())->toBeFalse();
});

// ─── canManageUsers ───────────────────────────────────────────────────────────

it('owner dan admin dapat mengelola user', function () {
    expect(UserRole::Owner->canManageUsers())->toBeTrue();
    expect(UserRole::Admin->canManageUsers())->toBeTrue();
    expect(UserRole::Cashier->canManageUsers())->toBeFalse();
});

// ─── canPrintReport ───────────────────────────────────────────────────────────

it('owner dan admin dapat mencetak laporan', function () {
    expect(UserRole::Owner->canPrintReport())->toBeTrue();
    expect(UserRole::Admin->canPrintReport())->toBeTrue();
    expect(UserRole::Cashier->canPrintReport())->toBeFalse();
});

// ─── canManageStock ───────────────────────────────────────────────────────────

it('owner dan admin dapat mengelola stok', function () {
    expect(UserRole::Owner->canManageStock())->toBeTrue();
    expect(UserRole::Admin->canManageStock())->toBeTrue();
    expect(UserRole::Cashier->canManageStock())->toBeFalse();
});

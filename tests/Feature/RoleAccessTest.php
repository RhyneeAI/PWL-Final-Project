<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeRoleUser(UserRole $role): User
{
    return User::factory()->create([
        'role'      => $role,
        'is_active' => true,
    ]);
}

// ─── Owner ────────────────────────────────────────────────────────────────────

it('owner dapat akses semua route yang dilindungi role', function () {
    $user = makeRoleUser(UserRole::Owner);

    $this->actingAs($user)
        ->get(route('branch.index'))->assertOk()
        ->get(route('user.index'))->assertOk()
        ->get(route('category.index'))->assertOk()
        ->get(route('product.index'))->assertOk()
        ->get(route('report.index'))->assertOk()
        ->get(route('settings.index'))->assertOk()
        ->get(route('stock-mutation.index'))->assertOk()
        ->get(route('transaction.index'))->assertOk();
});

// ─── Manajer Toko ─────────────────────────────────────────────────────────────

it('manager tidak dapat akses cabang tapi dapat akses master data lain', function () {
    $user = makeRoleUser(UserRole::Manager);

    $this->actingAs($user)
        ->get(route('branch.index'))->assertForbidden()
        ->get(route('user.index'))->assertOk()
        ->get(route('category.index'))->assertOk()
        ->get(route('product.index'))->assertOk()
        ->get(route('report.index'))->assertOk()
        ->get(route('settings.index'))->assertOk()
        ->get(route('stock-mutation.index'))->assertOk()
        ->get(route('transaction.index'))->assertOk();
});

// ─── Kasir ────────────────────────────────────────────────────────────────────

it('kasir hanya dapat akses dashboard dan transaksi', function () {
    $user = makeRoleUser(UserRole::Cashier);

    $this->actingAs($user)
        ->get(route('dashboard'))->assertOk()
        ->get(route('transaction.index'))->assertOk()
        ->get(route('branch.index'))->assertForbidden()
        ->get(route('report.index'))->assertForbidden()
        ->get(route('stock-mutation.index'))->assertForbidden();
});

// ─── Pegawai Gudang ───────────────────────────────────────────────────────────

it('pegawai gudang hanya dapat akses dashboard dan stok', function () {
    $user = makeRoleUser(UserRole::Warehouse);

    $this->actingAs($user)
        ->get(route('dashboard'))->assertOk()
        ->get(route('stock-mutation.index'))->assertOk()
        ->get(route('transaction.index'))->assertForbidden()
        ->get(route('report.index'))->assertForbidden()
        ->get(route('product.index'))->assertForbidden();
});

// ─── Akun nonaktif ────────────────────────────────────────────────────────────

it('user nonaktif di-logout saat akses route terproteksi', function () {
    $user = makeRoleUser(UserRole::Owner);
    $user->update(['is_active' => false]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

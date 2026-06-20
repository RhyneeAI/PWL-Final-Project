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

    $this->actingAs($user)->get(route('branches.index'))->assertOk();
    $this->actingAs($user)->get(route('users.index'))->assertOk();
    $this->actingAs($user)->get(route('categories.index'))->assertOk();
    $this->actingAs($user)->get(route('products.index'))->assertOk();
    $this->actingAs($user)->get(route('suppliers.index'))->assertOk();
    $this->actingAs($user)->get(route('report.index'))->assertOk();
    $this->actingAs($user)->get(route('settings.index'))->assertOk();
    $this->actingAs($user)->get(route('stock-mutation.index'))->assertOk();
    $this->actingAs($user)->get(route('transaction.index'))->assertOk();
});

// ─── Manager ──────────────────────────────────────────────────────────────────

it('manager tidak dapat akses cabang tapi dapat akses master data lain', function () {
    $user = makeRoleUser(UserRole::Manager);

    $this->actingAs($user)->get(route('branches.index'))->assertForbidden();
    $this->actingAs($user)->get(route('users.index'))->assertOk();
    $this->actingAs($user)->get(route('categories.index'))->assertOk();
    $this->actingAs($user)->get(route('products.index'))->assertOk();
    $this->actingAs($user)->get(route('suppliers.index'))->assertOk();
    $this->actingAs($user)->get(route('report.index'))->assertOk();
    $this->actingAs($user)->get(route('settings.index'))->assertOk();
    $this->actingAs($user)->get(route('stock-mutation.index'))->assertOk();
    $this->actingAs($user)->get(route('transaction.index'))->assertOk();
});

// ─── Kasir ────────────────────────────────────────────────────────────────────

it('kasir dapat akses dashboard transaksi dan lihat produk', function () {
    $user = makeRoleUser(UserRole::Cashier);

    $this->actingAs($user)->get(route('dashboard'))->assertOk();
    $this->actingAs($user)->get(route('transaction.index'))->assertOk();
    $this->actingAs($user)->get(route('products.index'))->assertOk();
    $this->actingAs($user)->get(route('branches.index'))->assertForbidden();
    $this->actingAs($user)->get(route('report.index'))->assertForbidden();
    $this->actingAs($user)->get(route('stock-mutation.index'))->assertForbidden();
    $this->actingAs($user)->get(route('suppliers.index'))->assertForbidden();
});

it('kasir tidak melihat tombol kelola produk di halaman produk', function () {
    $user = makeRoleUser(UserRole::Cashier);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertDontSee('+ Tambah Produk')
        ->assertDontSee('>Edit</button>')
        ->assertDontSee('>Hapus</button>');
});

// ─── Pegawai Gudang ───────────────────────────────────────────────────────────

it('pegawai gudang dapat akses dashboard stok dan lihat produk', function () {
    $user = makeRoleUser(UserRole::Warehouse);

    $this->actingAs($user)->get(route('dashboard'))->assertOk();
    $this->actingAs($user)->get(route('stock-mutation.index'))->assertOk();
    $this->actingAs($user)->get(route('products.index'))->assertOk();
    $this->actingAs($user)->get(route('suppliers.index'))->assertOk();
    $this->actingAs($user)->get(route('transaction.index'))->assertForbidden();
    $this->actingAs($user)->get(route('report.index'))->assertForbidden();
});

it('pegawai gudang tidak melihat tombol kelola produk di halaman produk', function () {
    $user = makeRoleUser(UserRole::Warehouse);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertDontSee('+ Tambah Produk')
        ->assertDontSee('>Edit</button>')
        ->assertDontSee('>Hapus</button>');
});

it('pegawai gudang tidak melihat tombol kelola supplier di halaman supplier', function () {
    $user = makeRoleUser(UserRole::Warehouse);

    $this->actingAs($user)
        ->get(route('suppliers.index'))
        ->assertOk()
        ->assertDontSee('+ Tambah Supplier')
        ->assertDontSee('>Edit</button>')
        ->assertDontSee('>Hapus</button>');
});

// ─── Akun nonaktif ────────────────────────────────────────────────────────────

it('user nonaktif di-logout saat akses route terproteksi', function () {
    $user = makeRoleUser(UserRole::Owner);
    $user->update(['is_active' => false]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

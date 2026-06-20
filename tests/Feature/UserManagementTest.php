<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('owner dapat membuat pengguna dengan semua role', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);

    $this->actingAs($owner)
        ->post(route('users.store'), [
            'name' => 'Owner Baru',
            'username' => 'ownerbaru',
            'email' => 'ownerbaru@example.com',
            'password' => 'password123',
            'role' => UserRole::Owner->value,
            'is_active' => true,
        ])
        ->assertRedirect(route('users.index'));

    expect(User::where('username', 'ownerbaru')->first()?->role)->toBe(UserRole::Owner);
});

it('manager tidak dapat membuat pengguna dengan role owner', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);

    $this->actingAs($manager)
        ->post(route('users.store'), [
            'name' => 'Owner Ilegal',
            'username' => 'ownerilegal',
            'email' => 'ownerilegal@example.com',
            'password' => 'password123',
            'role' => UserRole::Owner->value,
            'is_active' => true,
        ])
        ->assertSessionHasErrors('role');

    expect(User::where('username', 'ownerilegal')->exists())->toBeFalse();
});

it('manager dapat membuat pengguna dengan role manager kasir atau gudang', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);

    $this->actingAs($manager)
        ->post(route('users.store'), [
            'name' => 'Kasir Baru',
            'username' => 'kasirbaru',
            'email' => 'kasirbaru@example.com',
            'password' => 'password123',
            'role' => UserRole::Cashier->value,
            'is_active' => true,
        ])
        ->assertRedirect(route('users.index'));

    expect(User::where('username', 'kasirbaru')->first()?->role)->toBe(UserRole::Cashier);
});

it('manager tidak dapat mengubah profil pengguna lain selain role dan status', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $kasir = User::factory()->create([
        'role' => UserRole::Cashier,
        'name' => 'Kasir Lama',
        'username' => 'kasirlama',
        'email' => 'kasirlama@example.com',
    ]);

    $this->actingAs($manager)
        ->put(route('users.update', $kasir), [
            'role' => UserRole::Warehouse->value,
            'is_active' => false,
        ])
        ->assertRedirect(route('users.index'));

    $kasir->refresh();

    expect($kasir->name)->toBe('Kasir Lama')
        ->and($kasir->username)->toBe('kasirlama')
        ->and($kasir->email)->toBe('kasirlama@example.com')
        ->and($kasir->role)->toBe(UserRole::Warehouse)
        ->and($kasir->is_active)->toBeFalse();
});

it('manager tidak dapat mengubah akun owner', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $owner = User::factory()->create(['role' => UserRole::Owner, 'is_active' => true]);

    $this->actingAs($manager)
        ->get(route('users.edit', $owner))
        ->assertForbidden();

    $this->actingAs($manager)
        ->put(route('users.update', $owner), [
            'is_active' => false,
        ])
        ->assertForbidden();

    expect($owner->fresh()->is_active)->toBeTrue();
});

it('manager tidak melihat aksi edit dan hapus untuk owner di halaman index', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    User::factory()->create(['role' => UserRole::Owner, 'username' => 'ownerutama']);

    $this->actingAs($manager)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('ownerutama')
        ->assertDontSee(route('users.edit', User::where('username', 'ownerutama')->first()));
});

it('owner dapat mengubah profil sendiri', function () {
    $owner = User::factory()->create([
        'role' => UserRole::Owner,
        'name' => 'Nama Lama',
        'username' => 'ownerlama',
        'email' => 'lama@example.com',
    ]);

    $this->actingAs($owner)
        ->put(route('users.update', $owner), [
            'name' => 'Nama Baru',
            'username' => 'ownerbaru',
            'email' => 'baru@example.com',
            'role' => UserRole::Owner->value,
            'is_active' => true,
        ])
        ->assertRedirect(route('users.index'));

    $owner->refresh();

    expect($owner->name)->toBe('Nama Baru')
        ->and($owner->username)->toBe('ownerbaru')
        ->and($owner->email)->toBe('baru@example.com');
});

it('halaman create manager tidak menampilkan opsi role owner', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);

    $this->actingAs($manager)
        ->get(route('users.create'))
        ->assertOk()
        ->assertDontSee('value="owner"', false)
        ->assertSee('value="manager"', false)
        ->assertSee('value="cashier"', false)
        ->assertSee('value="warehouse"', false);
});

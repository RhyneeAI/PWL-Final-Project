<?php

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createBranch(string $name = 'MyFanel Bandung'): Branch
{
    return Branch::create([
        'code' => 'BR-' . uniqid(),
        'name' => $name,
        'address' => 'Jl. Test',
        'phone' => '+62 821-1111-111',
        'is_active' => true,
    ]);
}

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

    $user = User::where('username', 'ownerbaru')->first();

    expect($user?->role)->toBe(UserRole::Owner)
        ->and($user?->branchLabel())->toBe('Kantor Pusat')
        ->and($user?->branches)->toBeEmpty();
});

it('owner dapat membuat kasir dengan cabang tertentu', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);
    $branch = createBranch();

    $this->actingAs($owner)
        ->post(route('users.store'), [
            'name' => 'Kasir Cabang',
            'username' => 'kasircabang',
            'email' => 'kasircabang@example.com',
            'password' => 'password123',
            'role' => UserRole::Cashier->value,
            'branch_id' => $branch->id,
            'is_active' => true,
        ])
        ->assertRedirect(route('users.index'));

    $user = User::where('username', 'kasircabang')->first();

    expect($user?->branchLabel())->toBe('MyFanel Bandung')
        ->and($user?->primaryBranchId())->toBe($branch->id);
});

it('manager tidak dapat membuat pengguna dengan role owner', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $branch = createBranch();
    $manager->branches()->sync([$branch->id]);

    $this->actingAs($manager)
        ->post(route('users.store'), [
            'name' => 'Owner Ilegal',
            'username' => 'ownerilegal',
            'email' => 'ownerilegal@example.com',
            'password' => 'password123',
            'role' => UserRole::Owner->value,
            'branch_id' => $branch->id,
            'is_active' => true,
        ])
        ->assertSessionHasErrors('role');

    expect(User::where('username', 'ownerilegal')->exists())->toBeFalse();
});

it('manager dapat membuat pengguna dengan role manager kasir atau gudang', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $branch = createBranch();
    $manager->branches()->sync([$branch->id]);

    $this->actingAs($manager)
        ->post(route('users.store'), [
            'name' => 'Kasir Baru',
            'username' => 'kasirbaru',
            'email' => 'kasirbaru@example.com',
            'password' => 'password123',
            'role' => UserRole::Cashier->value,
            'branch_id' => $branch->id,
            'is_active' => true,
        ])
        ->assertRedirect(route('users.index'));

    expect(User::where('username', 'kasirbaru')->first()?->role)->toBe(UserRole::Cashier);
});

it('manager tidak dapat membuat pengguna di cabang yang tidak diaksesnya', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $branch = createBranch('MyFanel Bandung');
    $otherBranch = createBranch('MyFanel Jakarta');
    $manager->branches()->sync([$branch->id]);

    $this->actingAs($manager)
        ->post(route('users.store'), [
            'name' => 'Kasir Jakarta',
            'username' => 'kasirjkt',
            'email' => 'kasirjkt@example.com',
            'password' => 'password123',
            'role' => UserRole::Cashier->value,
            'branch_id' => $otherBranch->id,
            'is_active' => true,
        ])
        ->assertSessionHasErrors('branch_id');
});

it('manager tidak dapat mengubah profil pengguna lain selain role dan status', function () {
    $manager = User::factory()->create(['role' => UserRole::Manager]);
    $branch = createBranch();
    $manager->branches()->sync([$branch->id]);
    $kasir = User::factory()->create([
        'role' => UserRole::Cashier,
        'name' => 'Kasir Lama',
        'username' => 'kasirlama',
        'email' => 'kasirlama@example.com',
    ]);
    $kasir->branches()->sync([$branch->id]);

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
        ->and($kasir->is_active)->toBeFalse()
        ->and($kasir->primaryBranchId())->toBe($branch->id);
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
    $branch = createBranch();
    $manager->branches()->sync([$branch->id]);

    $this->actingAs($manager)
        ->get(route('users.create'))
        ->assertOk()
        ->assertDontSee('value="owner"', false)
        ->assertSee('value="manager"', false)
        ->assertSee('value="cashier"', false)
        ->assertSee('value="warehouse"', false);
});

it('halaman index menampilkan kolom cabang dan status', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner, 'is_active' => true]);
    $branch = createBranch();
    $kasir = User::factory()->create(['role' => UserRole::Cashier, 'is_active' => false]);
    $kasir->branches()->sync([$branch->id]);

    $this->actingAs($owner)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Kantor Pusat')
        ->assertSee('MyFanel Bandung')
        ->assertSee('status-badge-inactive');
});

it('role non owner wajib memilih cabang saat dibuat', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);

    $this->actingAs($owner)
        ->post(route('users.store'), [
            'name' => 'Kasir Tanpa Cabang',
            'username' => 'kasirtanpacabang',
            'email' => 'tanpa@example.com',
            'password' => 'password123',
            'role' => UserRole::Cashier->value,
            'is_active' => true,
        ])
        ->assertSessionHasErrors('branch_id');
});

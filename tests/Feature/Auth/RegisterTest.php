<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── Show register page ───────────────────────────────────────────────────────

it('dapat menampilkan halaman register', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertViewIs('auth.register');
});

it('redirect ke dashboard jika sudah login saat akses register', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('register'))
        ->assertRedirect(route('dashboard'));
});

// ─── Register success ─────────────────────────────────────────────────────────

it('user baru dapat mendaftar dengan data valid', function () {
    $response = $this->post(route('register'), [
        'name'                  => 'Kasir Baru',
        'email'                 => 'kasirbaru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'name'      => 'Kasir Baru',
        'email'     => 'kasirbaru@test.com',
        'role'      => UserRole::Cashier->value,
        'is_active' => true,
    ]);

    $this->assertAuthenticated();
});

it('user langsung login setelah register berhasil', function () {
    $this->post(route('register'), [
        'name'                  => 'Kasir Baru',
        'email'                 => 'kasirbaru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $user = User::where('email', 'kasirbaru@test.com')->first();
    $this->assertAuthenticatedAs($user);
});

// ─── Register failure ─────────────────────────────────────────────────────────

it('gagal register jika email sudah terdaftar', function () {
    User::factory()->create(['email' => 'ada@test.com']);

    $this->post(route('register'), [
        'name'                  => 'User Baru',
        'email'                 => 'ada@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('gagal register jika konfirmasi password tidak cocok', function () {
    $this->post(route('register'), [
        'name'                  => 'User Baru',
        'email'                 => 'baru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'beda-password',
    ])->assertSessionHasErrors('password');

    $this->assertGuest();
});

// ─── Validation ───────────────────────────────────────────────────────────────

it('validasi register: nama wajib diisi', function () {
    $this->post(route('register'), [
        'name'                  => '',
        'email'                 => 'baru@test.com',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('name');
});

it('validasi register: email wajib diisi', function () {
    $this->post(route('register'), [
        'name'                  => 'User Baru',
        'email'                 => '',
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors('email');
});

it('validasi register: password minimal 6 karakter', function () {
    $this->post(route('register'), [
        'name'                  => 'User Baru',
        'email'                 => 'baru@test.com',
        'password'              => '123',
        'password_confirmation' => '123',
    ])->assertSessionHasErrors('password');
});

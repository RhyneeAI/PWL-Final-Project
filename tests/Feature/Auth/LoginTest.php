<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

// ─── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Buat user aktif dengan password 'password123'.
 */
function makeUser(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'password'  => bcrypt('password123'),
        'is_active' => true,
    ], $attributes));
}

// ─── Show login page ──────────────────────────────────────────────────────────

it('dapat menampilkan halaman login', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertViewIs('auth.login');
});

it('redirect ke dashboard jika sudah login', function () {
    $this->actingAs(makeUser())
        ->get(route('login'))
        ->assertRedirect(route('dashboard'));
});

// ─── Login success ────────────────────────────────────────────────────────────

it('owner dapat login dengan kredensial valid', function () {
    $user = makeUser(['role' => UserRole::Owner]);

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('admin dapat login dengan kredensial valid', function () {
    $user = makeUser(['role' => UserRole::Admin]);

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('kasir dapat login dengan kredensial valid', function () {
    $user = makeUser(['role' => UserRole::Cashier]);

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('session di-regenerate setelah login berhasil', function () {
    $user = makeUser();
    $oldToken = session()->token();

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password123',
    ]);

    expect(session()->token())->not->toBe($oldToken);
});

// ─── Login failure ────────────────────────────────────────────────────────────

it('gagal login jika password salah', function () {
    $user = makeUser();

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'salah-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('gagal login jika email tidak terdaftar', function () {
    $this->post(route('login'), [
        'email'    => 'tidakada@test.com',
        'password' => 'password123',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('gagal login jika akun dinonaktifkan', function () {
    $user = makeUser(['is_active' => false]);

    $this->post(route('login'), [
        'email'    => $user->email,
        'password' => 'password123',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

// ─── Validation ───────────────────────────────────────────────────────────────

it('validasi: email wajib diisi', function () {
    $this->post(route('login'), ['email' => '', 'password' => 'password123'])
        ->assertSessionHasErrors('email');
});

it('validasi: email harus format yang benar', function () {
    $this->post(route('login'), ['email' => 'bukan-email', 'password' => 'password123'])
        ->assertSessionHasErrors('email');
});

it('validasi: password wajib diisi', function () {
    $this->post(route('login'), ['email' => 'test@test.com', 'password' => ''])
        ->assertSessionHasErrors('password');
});

it('validasi: password minimal 6 karakter', function () {
    $this->post(route('login'), ['email' => 'test@test.com', 'password' => '123'])
        ->assertSessionHasErrors('password');
});

// ─── Logout ───────────────────────────────────────────────────────────────────

it('user yang sudah login dapat logout', function () {
    $this->actingAs(makeUser())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('session di-invalidate setelah logout', function () {
    $user = makeUser();
    $this->actingAs($user);
    $oldToken = session()->token();

    $this->post(route('logout'));

    expect(session()->token())->not->toBe($oldToken);
});

it('guest yang akses logout di-redirect ke login', function () {
    $this->post(route('logout'))
        ->assertRedirect(route('login'));
});

// ─── Rate Limiting ────────────────────────────────────────────────────────────

it('dibatasi setelah 5 kali percobaan login gagal', function () {
    $user = makeUser(['email' => 'victim@test.com']);

    // Clear rate limiter sebelum test
    RateLimiter::clear('victim@test.com|127.0.0.1');

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login'), [
            'email'    => 'victim@test.com',
            'password' => 'wrong',
        ]);
    }

    $response = $this->post(route('login'), [
        'email'    => 'victim@test.com',
        'password' => 'wrong',
    ]);

    $response->assertSessionHasErrors('email');
    expect(session('errors')->get('email')[0])->toContain('Terlalu banyak percobaan');
});

it('rate limiter di-reset setelah login berhasil', function () {
    $user = makeUser(['email' => 'clean@test.com']);
    $throttleKey = 'clean@test.com|127.0.0.1';

    // Simulasi 2 percobaan gagal dulu
    RateLimiter::hit($throttleKey);
    RateLimiter::hit($throttleKey);

    // Login berhasil → rate limiter harus di-clear
    $this->post(route('login'), [
        'email'    => 'clean@test.com',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    expect(RateLimiter::attempts($throttleKey))->toBe(0);
});

<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

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
        'username' => $user->username,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('supervisor dapat login dengan kredensial valid', function () {
    $user = makeUser(['role' => UserRole::Supervisor]);

    $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('kasir dapat login dengan kredensial valid', function () {
    $user = makeUser(['role' => UserRole::Cashier]);

    $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('login tidak case-sensitive untuk username', function () {
    $user = makeUser(['username' => 'kasirbandung']);

    $this->post(route('login'), [
        'username' => 'KasirBandung',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('session di-regenerate setelah login berhasil', function () {
    $user = makeUser();
    $oldToken = session()->token();

    $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'password123',
    ]);

    expect(session()->token())->not->toBe($oldToken);
});

// ─── Login failure ────────────────────────────────────────────────────────────

it('gagal login jika password salah', function () {
    $user = makeUser();

    $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'salah-password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('gagal login jika username tidak terdaftar', function () {
    $this->post(route('login'), [
        'username' => 'tidakada',
        'password' => 'password123',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

it('gagal login jika akun dinonaktifkan', function () {
    $user = makeUser(['is_active' => false]);

    $this->post(route('login'), [
        'username' => $user->username,
        'password' => 'password123',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

// ─── Validation ───────────────────────────────────────────────────────────────

it('validasi: username wajib diisi', function () {
    $this->post(route('login'), ['username' => '', 'password' => 'password123'])
        ->assertSessionHasErrors('username');
});

it('validasi: username minimal 3 karakter', function () {
    $this->post(route('login'), ['username' => 'ab', 'password' => 'password123'])
        ->assertSessionHasErrors('username');
});

it('validasi: password wajib diisi', function () {
    $this->post(route('login'), ['username' => 'testuser', 'password' => ''])
        ->assertSessionHasErrors('password');
});

it('validasi: password minimal 6 karakter', function () {
    $this->post(route('login'), ['username' => 'testuser', 'password' => '123'])
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
    makeUser(['username' => 'victim']);

    RateLimiter::clear('victim|127.0.0.1');

    foreach (range(1, 5) as $attempt) {
        $this->post(route('login'), [
            'username' => 'victim',
            'password' => 'wrongpass',
        ]);
    }

    $response = $this->post(route('login'), [
        'username' => 'victim',
        'password' => 'wrongpass',
    ]);

    $response->assertSessionHasErrors('username');
    expect(session('errors')->get('username')[0])->toContain('Terlalu banyak percobaan');
});

it('rate limiter di-reset setelah login berhasil', function () {
    $user = makeUser(['username' => 'clean']);
    $throttleKey = 'clean|127.0.0.1';

    RateLimiter::hit($throttleKey);
    RateLimiter::hit($throttleKey);

    $this->post(route('login'), [
        'username' => 'clean',
        'password' => 'password123',
    ])->assertRedirect(route('dashboard'));

    expect(RateLimiter::attempts($throttleKey))->toBe(0);
});

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ─── Guest routes (belum login) ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ─── Auth routes (sudah login) ────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (semua role)
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Owner only — kelola cabang
    Route::middleware('role:owner')->group(function () {
        Route::get('/branches', function () {
            return view('master-data.branch.index');
        })->name('branch.index');
    });

    // Owner + Manajer Toko
    Route::middleware('role:owner,manager')->group(function () {
        Route::get('/users', function () {
            return view('master-data.user.index');
        })->name('user.index');

        Route::get('/categories', function () {
            return view('master-data.category.index');
        })->name('category.index');

        Route::get('/reports', function () {
            return view('reports.index');
        })->name('report.index');

        Route::get('/settings', function () {
            return view('settings.index');
        })->name('settings.index');
    });

    // Semua role operasional — lihat produk (read-only untuk kasir & gudang)
    Route::middleware('role:owner,manager,cashier,warehouse')->group(function () {
        Route::get('/products', function () {
            return view('master-data.product.index');
        })->name('product.index');
    });

    // Owner + Manajer Toko + Pegawai Gudang — stok
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::get('/stock-mutations', function () {
            return view('transaksi.stock-in.index');
        })->name('stock-mutation.index');
    });

    // Owner + Manajer Toko + Kasir — transaksi
    Route::middleware('role:owner,manager,cashier')->group(function () {
        Route::get('/transactions', function () {
            return view('transaksi.transaction.index');
        })->name('transaction.index');
    });
});

// ─── Fallback (route tidak ditemukan) ────────────────────────────────────────
Route::fallback(function () {
    if (auth()->check()) {
        return response()->view('errors.404', [], 404);
    }

    return redirect()->route('login')
        ->with('error', 'Silakan login terlebih dahulu.');
});

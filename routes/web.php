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
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (semua role)
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Owner only
    Route::middleware('role:owner')->group(function () {
        Route::get('/branches', function () {
            return view('master-data.branch.index');
        })->name('branch.index');

        Route::get('/users', function () {
            return view('master-data.user.index');
        })->name('user.index');
    });

    // Owner + Admin
    Route::middleware('role:owner,admin')->group(function () {
        Route::get('/categories', function () {
            return view('master-data.category.index');
        })->name('category.index');

        Route::get('/products', function () {
            return view('master-data.product.index');
        })->name('product.index');

        Route::get('/stock-mutations', function () {
            return view('transaksi.stock-mutation.index');
        })->name('stock-mutation.index');

        Route::get('/reports', function () {
            return view('reports.index');
        })->name('report.index');

        Route::get('/settings', function () {
            return view('settings.index');
        })->name('settings.index');
    });

    // Semua role (owner, admin, cashier)
    Route::get('/transactions', function () {
        return view('transaksi.transaction.index');
    })->name('transaction.index');
});

// ─── Fallback (route tidak ditemukan) ────────────────────────────────────────
// Sudah login → tampilkan 404, belum login → redirect ke login
Route::fallback(function () {
    if (auth()->check()) {
        return response()->view('errors.404', [], 404);
    }

    return redirect()->route('login')
        ->with('error', 'Silakan login terlebih dahulu.');
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
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

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Owner only — kelola cabang
    Route::middleware('role:owner')->group(function () {
        Route::resource('branches', BranchController::class)->except(['show']);
    });

    // Owner + Manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::get('/reports', function () {
            return view('reports.index');
        })->name('report.index');

        Route::get('/settings', function () {
            return view('settings.index');
        })->name('settings.index');
    });

    // Produk — index untuk semua role operasional
    Route::middleware('role:owner,manager,cashier,warehouse')->group(function () {
        Route::resource('products', ProductController::class)->only(['index']);
    });

    // Produk — CRUD untuk owner & manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    // Supplier — index untuk owner, manager & gudang
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::resource('suppliers', SupplierController::class)->only(['index']);
    });

    // Supplier — CRUD untuk owner & manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);
    });

    // Stok
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::get('/stock-mutations', function () {
            return view('transaksi.stock-in.index');
        })->name('stock-mutation.index');
    });

    // Transaksi
    Route::middleware('role:owner,manager,cashier')->group(function () {
        Route::get('/transactions', function () {
            return view('transaksi.transaction.index');
        })->name('transaction.index');
    });
});

Route::fallback(function () {
    if (auth()->check()) {
        return response()->view('errors.404', [], 404);
    }

    return redirect()->route('login')
        ->with('error', 'Silakan login terlebih dahulu.');
});

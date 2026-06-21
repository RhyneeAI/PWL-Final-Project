<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
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
        Route::patch('branches/{branch}/active', [BranchController::class, 'updateActive'])->name('branches.update-active');
        Route::resource('branches', BranchController::class)->except(['show']);
    });

    // Owner + Manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::patch('users/{user}/active', [UserController::class, 'updateActive'])->name('users.update-active');
        Route::resource('users', UserController::class)->except(['show']);

        Route::patch('categories/{category}/active', [CategoryController::class, 'updateActive'])->name('categories.update-active');
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::get('/reports', [ReportController::class, 'index'])->name('report.index');
        Route::get('/reports/pdf', [ReportController::class, 'pdf'])->name('report.pdf');
        Route::get('/reports/excel', [ReportController::class, 'excel'])->name('report.excel');

        Route::get('/settings', function () {
            return view('settings.index');
        })->name('settings.index');
    });

    // Produk — index & detail untuk semua role operasional
    Route::middleware('role:owner,manager,cashier,warehouse')->group(function () {
        Route::resource('products', ProductController::class)->only(['index', 'show']);
    });

    // Produk — CRUD untuk owner & manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::patch('products/{product}/active', [ProductController::class, 'updateActive'])->name('products.update-active');
        Route::resource('products', ProductController::class)->except(['index', 'show']);
    });

    // Supplier — index untuk owner, manager & gudang
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::resource('suppliers', SupplierController::class)->only(['index']);
    });

    // Supplier — CRUD untuk owner & manager
    Route::middleware('role:owner,manager')->group(function () {
        Route::patch('suppliers/{supplier}/active', [SupplierController::class, 'updateActive'])->name('suppliers.update-active');
        Route::resource('suppliers', SupplierController::class)->except(['index', 'show']);
    });

    // Stok — Stok Masuk
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::get('/stock-mutations', [StockInController::class, 'index'])->name('stock-mutation.index');
        Route::get('/stock-mutations/create', [StockInController::class, 'create'])->name('stock-mutation.create');
        Route::post('/stock-mutations', [StockInController::class, 'store'])->name('stock-mutation.store');
        Route::get('/stock-mutations/{referenceCode}', [StockInController::class, 'show'])->name('stock-mutation.show');
    });

    // Stok — Stok Keluar
    Route::middleware('role:owner,manager,warehouse')->group(function () {
        Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
        Route::get('/stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
        Route::post('/stock-out', [StockOutController::class, 'store'])->name('stock-out.store');
        Route::get('/stock-out/{referenceCode}', [StockOutController::class, 'show'])->name('stock-out.show');
    });

    // Transaksi Penjualan
    Route::middleware('role:owner,manager,cashier')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
        Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transaction.create');
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transaction.show');
    });
});

Route::fallback(function () {
    if (auth()->check()) {
        return response()->view('errors.404', [], 404);
    }

    return redirect()->route('login')
        ->with('error', 'Silakan login terlebih dahulu.');
});

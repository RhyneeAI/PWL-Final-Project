<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // Owner only — kelola cabang
    Route::middleware('role:owner')->group(function () {
        Route::patch('branches/{branch}/active', [BranchController::class, 'updateActive'])->name('branches.update-active');
        Route::resource('branches', BranchController::class)->except(['show']);
    });

    // Owner + Manager
    Route::middleware('role:owner,supervisor')->group(function () {
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

    // Produk
    Route::get('products', [ProductController::class, 'index'])->name('products.index')->middleware('role:owner,supervisor,cashier,warehouse');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create')->middleware('role:owner,supervisor');
    Route::post('products', [ProductController::class, 'store'])->name('products.store')->middleware('role:owner,supervisor');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('role:owner,supervisor,cashier,warehouse');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('role:owner,supervisor');
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('role:owner,supervisor');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('role:owner,supervisor');
    Route::patch('products/{product}/active', [ProductController::class, 'updateActive'])->name('products.update-active')->middleware('role:owner,supervisor');

    // Supplier
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index')->middleware('role:owner,supervisor,warehouse');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create')->middleware('role:owner,supervisor');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('role:owner,supervisor');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show')->middleware('role:owner,supervisor');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('role:owner,supervisor');
    Route::patch('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('role:owner,supervisor');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('role:owner,supervisor');
    Route::patch('suppliers/{supplier}/active', [SupplierController::class, 'updateActive'])->name('suppliers.update-active')->middleware('role:owner,supervisor');

    // Stok — Stok Masuk
    Route::middleware('role:owner,supervisor,warehouse')->group(function () {
        Route::get('/stock-mutations', [StockInController::class, 'index'])->name('stock-mutation.index');
        Route::get('/stock-mutations/create', [StockInController::class, 'create'])->name('stock-mutation.create');
        Route::post('/stock-mutations', [StockInController::class, 'store'])->name('stock-mutation.store');
        Route::get('/stock-mutations/{referenceCode}', [StockInController::class, 'show'])->name('stock-mutation.show');
    });

    // Stok — Stok Keluar
    Route::middleware('role:owner,supervisor,warehouse')->group(function () {
        Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
        Route::get('/stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
        Route::post('/stock-out', [StockOutController::class, 'store'])->name('stock-out.store');
        Route::get('/stock-out/{referenceCode}', [StockOutController::class, 'show'])->name('stock-out.show');
    });

    // Transaksi Penjualan
    Route::middleware('role:owner,supervisor,cashier')->group(function () {
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

<?php

use App\Enums\StockMutationType;
use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createProductShowBranch(): Branch
{
    return Branch::create([
        'code' => 'BR-' . uniqid(),
        'name' => 'MyFanel Bandung',
        'address' => 'Jl. Test',
        'phone' => '+62 821-1111-111',
        'is_active' => true,
    ]);
}

function createProductForShow(Branch $branch, array $overrides = []): Product
{
    return Product::create(array_merge([
        'branch_id' => $branch->id,
        'code' => Product::generateNextCode($branch->id),
        'name' => 'Indomie Goreng',
        'unit' => 'pcs',
        'buy_price' => 3000,
        'sell_price' => 3500,
        'stock' => 20,
        'min_stock' => 5,
        'is_active' => true,
    ], $overrides));
}

it('kasir dapat melihat detail produk dan histori mutasi', function () {
    $branch = createProductShowBranch();
    $cashier = User::factory()->create(['role' => UserRole::Cashier]);
    $cashier->branches()->sync([$branch->id]);
    $product = createProductForShow($branch);
    $actor = User::factory()->create(['role' => UserRole::Warehouse]);
    $actor->branches()->sync([$branch->id]);
    $supplier = Supplier::create([
        'branch_id' => $branch->id,
        'code' => Supplier::generateNextCode($branch->id),
        'name' => 'PT Supplier',
        'is_active' => true,
    ]);

    StockMutation::create([
        'branch_id' => $branch->id,
        'product_id' => $product->id,
        'user_id' => $actor->id,
        'supplier_id' => $supplier->id,
        'reference_code' => 'STMM-001',
        'type' => StockMutationType::AdjustIn,
        'quantity_before' => 10,
        'quantity_change' => 10,
        'quantity_after' => 20,
        'buy_price' => 3000,
        'mutation_date' => now(),
    ]);

    $this->actingAs($cashier)
        ->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Indomie Goreng')
        ->assertSee('Histori Mutasi Stok')
        ->assertSee('Stok Masuk')
        ->assertSee('STMM-001')
        ->assertSee('+10');
});

it('halaman index produk menampilkan icon detail untuk semua role', function () {
    $branch = createProductShowBranch();
    $cashier = User::factory()->create(['role' => UserRole::Cashier]);
    $cashier->branches()->sync([$branch->id]);
    $product = createProductForShow($branch);

    $this->actingAs($cashier)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee('btn-action-show', false)
        ->assertSee(route('products.show', $product), false)
        ->assertDontSee('btn-action-edit', false);
});

it('warehouse tidak dapat melihat produk di cabang lain', function () {
    $branch = createProductShowBranch();
    $otherBranch = Branch::create([
        'code' => 'BR-JKT',
        'name' => 'MyFanel Jakarta',
        'address' => 'Jl. Jakarta',
        'phone' => '+62 821-2222-222',
        'is_active' => true,
    ]);
    $warehouse = User::factory()->create(['role' => UserRole::Warehouse]);
    $warehouse->branches()->sync([$branch->id]);
    $product = createProductForShow($otherBranch);

    $this->actingAs($warehouse)
        ->get(route('products.show', $product))
        ->assertForbidden();
});

it('histori mutasi produk mendukung filter tanggal dan pagination', function () {
    $branch = createProductShowBranch();
    $owner = User::factory()->create(['role' => UserRole::Owner]);
    $product = createProductForShow($branch);

    foreach (range(1, 12) as $day) {
        StockMutation::create([
            'branch_id' => $branch->id,
            'product_id' => $product->id,
            'user_id' => $owner->id,
            'reference_code' => 'STMM-' . str_pad((string) $day, 3, '0', STR_PAD_LEFT),
            'type' => StockMutationType::AdjustIn,
            'quantity_before' => $day,
            'quantity_change' => 1,
            'quantity_after' => $day + 1,
            'buy_price' => 1000,
            'mutation_date' => now()->startOfMonth()->addDays($day - 1),
        ]);
    }

    $this->actingAs($owner)
        ->get(route('products.show', $product))
        ->assertOk()
        ->assertSee('Menampilkan 1–10 dari 12 mutasi')
        ->assertDontSee('Petugas');

    $filterDate = now()->startOfMonth()->addDays(4)->format('Y-m-d');

    $this->actingAs($owner)
        ->get(route('products.show', [
            'product' => $product,
            'date_from' => $filterDate,
            'date_to' => $filterDate,
        ]))
        ->assertOk()
        ->assertSee('Menampilkan 1–1 dari 1 mutasi')
        ->assertSee(now()->startOfMonth()->addDays(4)->format('d M Y'));
});

<?php

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createStockInBranch(string $name = 'MyFanel Bandung'): Branch
{
    return Branch::create([
        'code' => 'BR-' . uniqid(),
        'name' => $name,
        'address' => 'Jl. Test',
        'phone' => '+62 821-1111-111',
        'is_active' => true,
    ]);
}

function createStockInProduct(Branch $branch, array $overrides = []): Product
{
    return Product::create(array_merge([
        'branch_id' => $branch->id,
        'code' => Product::generateNextCode($branch->id),
        'name' => 'Produk Test',
        'unit' => 'pcs',
        'buy_price' => 10000,
        'sell_price' => 12000,
        'stock' => 5,
        'min_stock' => 1,
        'is_active' => true,
    ], $overrides));
}

function createStockInSupplier(Branch $branch, array $overrides = []): Supplier
{
    return Supplier::create(array_merge([
        'branch_id' => $branch->id,
        'code' => Supplier::generateNextCode($branch->id),
        'name' => 'PT Supplier Test',
        'is_active' => true,
    ], $overrides));
}

it('warehouse dapat melihat halaman index stok masuk dengan toolbar pencarian', function () {
    $branch = createStockInBranch();
    $warehouse = User::factory()->create(['role' => UserRole::Warehouse]);
    $warehouse->branches()->sync([$branch->id]);

    $this->actingAs($warehouse)
        ->get(route('stock-mutation.index'))
        ->assertOk()
        ->assertSee('id="search-stock-in"', false)
        ->assertSee('Tambah Stok Masuk');
});

it('warehouse tidak melihat filter cabang di index stok masuk', function () {
    $branch = createStockInBranch();
    $warehouse = User::factory()->create(['role' => UserRole::Warehouse]);
    $warehouse->branches()->sync([$branch->id]);

    $this->actingAs($warehouse)
        ->get(route('stock-mutation.index'))
        ->assertOk()
        ->assertDontSee('id="filter-stock-in-branch"', false);
});

it('owner dapat mencatat stok masuk dan memperbarui stok produk', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);
    $branch = createStockInBranch();
    $supplier = createStockInSupplier($branch);
    $product = createStockInProduct($branch, ['buy_price' => 10000, 'stock' => 5]);

    $this->actingAs($owner)
        ->post(route('stock-mutation.store'), [
            'branch_id' => $branch->id,
            'supplier_id' => $supplier->id,
            'mutation_date' => now()->format('Y-m-d\TH:i'),
            'notes' => 'Pembelian awal',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 10,
                    'buy_price' => 11000,
                ],
            ],
        ])
        ->assertRedirect();

    $product->refresh();

    expect($product->stock)->toBe(15)
        ->and((float) $product->buy_price)->toBe(11000.0);

    $mutation = StockMutation::query()->where('product_id', $product->id)->first();

    expect($mutation)->not->toBeNull()
        ->and($mutation->reference_code)->toStartWith('STMM')
        ->and($mutation->quantity_before)->toBe(5)
        ->and($mutation->quantity_change)->toBe(10)
        ->and($mutation->quantity_after)->toBe(15)
        ->and($mutation->supplier_id)->toBe($supplier->id);
});

it('warehouse otomatis menggunakan cabang sendiri saat stok masuk', function () {
    $branch = createStockInBranch();
    $warehouse = User::factory()->create(['role' => UserRole::Warehouse]);
    $warehouse->branches()->sync([$branch->id]);
    $supplier = createStockInSupplier($branch);
    $product = createStockInProduct($branch);

    $this->actingAs($warehouse)
        ->post(route('stock-mutation.store'), [
            'supplier_id' => $supplier->id,
            'mutation_date' => now()->format('Y-m-d\TH:i'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'buy_price' => 15000,
                ],
            ],
        ])
        ->assertRedirect();

    expect(StockMutation::query()->first()?->branch_id)->toBe($branch->id);
});

it('halaman detail stok masuk menampilkan item pembelian', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);
    $branch = createStockInBranch();
    $supplier = createStockInSupplier($branch);
    $product = createStockInProduct($branch, ['name' => 'Indomie Goreng']);

    $this->actingAs($owner)
        ->post(route('stock-mutation.store'), [
            'branch_id' => $branch->id,
            'supplier_id' => $supplier->id,
            'mutation_date' => now()->format('Y-m-d\TH:i'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'buy_price' => 3500,
                ],
            ],
        ]);

    $referenceCode = StockMutation::query()->value('reference_code');

    $this->actingAs($owner)
        ->get(route('stock-mutation.show', $referenceCode))
        ->assertOk()
        ->assertSee($referenceCode)
        ->assertSee('Indomie Goreng')
        ->assertSee('PT Supplier Test');
});

it('index stok masuk menampilkan tombol aksi detail tanpa edit', function () {
    $owner = User::factory()->create(['role' => UserRole::Owner]);
    $branch = createStockInBranch();
    $supplier = createStockInSupplier($branch);
    $product = createStockInProduct($branch);

    $this->actingAs($owner)
        ->post(route('stock-mutation.store'), [
            'branch_id' => $branch->id,
            'supplier_id' => $supplier->id,
            'mutation_date' => now()->format('Y-m-d\TH:i'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'buy_price' => 5000,
                ],
            ],
        ]);

    $referenceCode = StockMutation::query()->value('reference_code');

    $this->actingAs($owner)
        ->get(route('stock-mutation.index'))
        ->assertOk()
        ->assertSee($referenceCode)
        ->assertSee('btn-action-show', false)
        ->assertDontSee('btn-action-edit', false);
});

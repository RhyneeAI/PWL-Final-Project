<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $branches = Branch::all();

        $productTemplates = [
            'Makanan Ringan' => [
                ['name' => 'Tango Wafer Coklat', 'unit' => 'pcs', 'buy_price' => 4500, 'sell_price' => 6000, 'min_stock' => 20],
                ['name' => 'Good Time Cookies', 'unit' => 'pcs', 'buy_price' => 6200, 'sell_price' => 8500, 'min_stock' => 15],
                ['name' => 'Lays Kentang Goreng', 'unit' => 'pcs', 'buy_price' => 8500, 'sell_price' => 11000, 'min_stock' => 10],
                ['name' => 'Oreo Coklat', 'unit' => 'pcs', 'buy_price' => 5800, 'sell_price' => 7800, 'min_stock' => 20],
            ],
            'Makanan Berat' => [
                ['name' => 'Indomie Goreng', 'unit' => 'pcs', 'buy_price' => 2800, 'sell_price' => 4000, 'min_stock' => 50],
                ['name' => 'Nasi Goreng Instan', 'unit' => 'pcs', 'buy_price' => 9500, 'sell_price' => 13000, 'min_stock' => 10],
                ['name' => 'Sarden ABC Kaleng', 'unit' => 'kaleng', 'buy_price' => 12000, 'sell_price' => 16000, 'min_stock' => 10],
                ['name' => 'Kornet Sapi Pronas', 'unit' => 'kaleng', 'buy_price' => 18500, 'sell_price' => 24000, 'min_stock' => 8],
            ],
            'Minuman' => [
                ['name' => 'Aqua Air Mineral 600ml', 'unit' => 'pcs', 'buy_price' => 2500, 'sell_price' => 4000, 'min_stock' => 50],
                ['name' => 'Coca Cola Kaleng', 'unit' => 'kaleng', 'buy_price' => 4500, 'sell_price' => 6500, 'min_stock' => 20],
                ['name' => 'Teh Botol Sosro', 'unit' => 'botol', 'buy_price' => 3800, 'sell_price' => 5500, 'min_stock' => 25],
                ['name' => 'Pocari Sweat 500ml', 'unit' => 'botol', 'buy_price' => 5200, 'sell_price' => 7500, 'min_stock' => 15],
            ],
            'Sembako' => [
                ['name' => 'Beras Ramos 5kg', 'unit' => 'sak', 'buy_price' => 48000, 'sell_price' => 58000, 'min_stock' => 5],
                ['name' => 'Minyak Goreng Bimoli 1L', 'unit' => 'botol', 'buy_price' => 18500, 'sell_price' => 23000, 'min_stock' => 10],
                ['name' => 'Gula Pasir Gulaku 1kg', 'unit' => 'pcs', 'buy_price' => 12500, 'sell_price' => 16000, 'min_stock' => 15],
                ['name' => 'Tepung Terigu Segitiga 1kg', 'unit' => 'pcs', 'buy_price' => 9500, 'sell_price' => 12500, 'min_stock' => 10],
                ['name' => 'Telur Ayam 1kg', 'unit' => 'pcs', 'buy_price' => 24000, 'sell_price' => 30000, 'min_stock' => 8],
            ],
            'Produk Rumah Tangga' => [
                ['name' => 'Sabun Cuci Piring Sunlight', 'unit' => 'botol', 'buy_price' => 7500, 'sell_price' => 10500, 'min_stock' => 10],
                ['name' => 'Pembersih Lantai So Klin', 'unit' => 'botol', 'buy_price' => 11000, 'sell_price' => 15500, 'min_stock' => 8],
                ['name' => 'Sapu Lantai', 'unit' => 'pcs', 'buy_price' => 18000, 'sell_price' => 25000, 'min_stock' => 5],
                ['name' => 'Kantong Plastik Sampah', 'unit' => 'pack', 'buy_price' => 8000, 'sell_price' => 11500, 'min_stock' => 12],
            ],
            'Perawatan Diri' => [
                ['name' => 'Sabun Mandi Lifebuoy', 'unit' => 'pcs', 'buy_price' => 3500, 'sell_price' => 5000, 'min_stock' => 20],
                ['name' => 'Shampoo Pantene 170ml', 'unit' => 'botol', 'buy_price' => 12500, 'sell_price' => 17000, 'min_stock' => 10],
                ['name' => 'Pasta Gigi Pepsodent', 'unit' => 'pcs', 'buy_price' => 6500, 'sell_price' => 9000, 'min_stock' => 15],
                ['name' => 'Deodorant Rexona', 'unit' => 'pcs', 'buy_price' => 11000, 'sell_price' => 15000, 'min_stock' => 10],
            ],
        ];

        $counters = [];
        foreach ($branches as $branch) {
            $counters[$branch->id] = Product::withTrashed()
                ->where('branch_id', $branch->id)
                ->where('code', 'like', $branch->productCodePrefix() . '-%')
                ->max('code');
            $counters[$branch->id] = $counters[$branch->id]
                ? (int) substr($counters[$branch->id], strlen($branch->productCodePrefix()) + 1)
                : 0;
        }

        foreach ($branches as $branch) {
            foreach ($productTemplates as $categoryName => $products) {
                $categoryId = $categories[$categoryName];

                foreach ($products as $product) {
                    $existing = Product::where('branch_id', $branch->id)
                        ->where('name', $product['name'])
                        ->first();

                    if ($existing) {
                        continue;
                    }

                    $counters[$branch->id]++;
                    $prefix = $branch->productCodePrefix();
                    $code = $prefix . '-' . str_pad((string) $counters[$branch->id], 3, '0', STR_PAD_LEFT);

                    Product::create([
                        'code'        => $code,
                        'category_id' => $categoryId,
                        'branch_id'   => $branch->id,
                        'barcode'     => null,
                        'name'        => $product['name'],
                        'unit'        => $product['unit'],
                        'buy_price'   => $product['buy_price'],
                        'sell_price'  => $product['sell_price'],
                        'stock'       => 0,
                        'min_stock'   => $product['min_stock'],
                        'is_active'   => true,
                    ]);
                }
            }
        }
    }
}

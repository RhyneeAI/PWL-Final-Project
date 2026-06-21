<?php

namespace Database\Seeders;

use App\Enums\StockMutationType;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockMutation;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMutationSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = User::where('role', 'warehouse')->get()->keyBy(fn ($u) => $u->branches->first()?->id);
        $branches = Branch::all();

        $initialStock = [
            'Tango Wafer Coklat' => 60,
            'Good Time Cookies' => 40,
            'Lays Kentang Goreng' => 35,
            'Oreo Coklat' => 50,
            'Indomie Goreng' => 120,
            'Nasi Goreng Instan' => 25,
            'Sarden ABC Kaleng' => 30,
            'Kornet Sapi Pronas' => 20,
            'Aqua Air Mineral 600ml' => 100,
            'Coca Cola Kaleng' => 60,
            'Teh Botol Sosro' => 70,
            'Pocari Sweat 500ml' => 40,
            'Beras Ramos 5kg' => 15,
            'Minyak Goreng Bimoli 1L' => 30,
            'Gula Pasir Gulaku 1kg' => 40,
            'Tepung Terigu Segitiga 1kg' => 25,
            'Telur Ayam 1kg' => 20,
            'Sabun Cuci Piring Sunlight' => 30,
            'Pembersih Lantai So Klin' => 20,
            'Sapu Lantai' => 12,
            'Kantong Plastik Sampah' => 35,
            'Sabun Mandi Lifebuoy' => 60,
            'Shampoo Pantene 170ml' => 25,
            'Pasta Gigi Pepsodent' => 40,
            'Deodorant Rexona' => 30,
        ];

        $date = now()->subDays(7);

        foreach ($branches as $branch) {
            $warehouse = $warehouses->get($branch->id) ?? User::where('role', 'warehouse')->first();

            $products = Product::where('branch_id', $branch->id)->get();

            foreach ($products as $product) {
                $qty = $initialStock[$product->name] ?? 20;

                StockMutation::create([
                    'branch_id'       => $branch->id,
                    'product_id'      => $product->id,
                    'user_id'         => $warehouse->id,
                    'reference_code'  => 'INIT-' . $branch->code . '-' . $product->id,
                    'type'            => StockMutationType::AdjustIn,
                    'quantity_before' => 0,
                    'quantity_change' => $qty,
                    'quantity_after'  => $qty,
                    'buy_price'       => $product->buy_price,
                    'notes'           => 'Stok awal',
                    'mutation_date'   => $date,
                ]);

                $product->update(['stock' => $qty]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Ringan', 'description' => 'Camilan dan makanan ringan'],
            ['name' => 'Makanan Berat', 'description' => 'Makanan siap saji dan instan'],
            ['name' => 'Minuman', 'description' => 'Minuman ringan dan kemasan'],
            ['name' => 'Sembako', 'description' => 'Bahan pokok dan kebutuhan dapur'],
            ['name' => 'Produk Rumah Tangga', 'description' => 'Peralatan kebersihan rumah'],
            ['name' => 'Perawatan Diri', 'description' => 'Produk perawatan pribadi'],
        ];

        foreach ($categories as $data) {
            Category::firstOrCreate(
                ['name' => $data['name']],
                $data + ['is_active' => true]
            );
        }
    }
}

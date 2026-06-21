<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $branchList = Branch::all()->keyBy('code');

        $suppliers = [
            // Bandung (existing)
            ['code' => 'SUPMB-001', 'branch_code' => 'BR-001', 'name' => 'PT Sumber Makmur', 'phone' => '022-1112233', 'email' => 'sumber@supplier.com', 'address' => 'Jl. Moh. Toha No. 10, Bandung'],
            ['code' => 'SUPMB-002', 'branch_code' => 'BR-001', 'name' => 'CV Fresh Food', 'phone' => '022-4445566', 'email' => 'fresh@supplier.com', 'address' => 'Jl. Soekarno Hatta No. 5, Bandung'],

            // Jakarta (existing)
            ['code' => 'SUPMJ-001', 'branch_code' => 'BR-002', 'name' => 'PT Distribusi Nusantara', 'phone' => '021-3334455', 'email' => 'distribusi@supplier.com', 'address' => 'Jl. Gatot Subroto No. 20, Jakarta'],
            ['code' => 'SUPMJ-002', 'branch_code' => 'BR-002', 'name' => 'UD Berkah Jaya', 'phone' => '021-7778899', 'email' => 'berkah@supplier.com', 'address' => 'Jl. Tanah Abang No. 8, Jakarta'],

            // Surabaya
            ['code' => 'SUPMS-001', 'branch_code' => 'BR-003', 'name' => 'PT Sumber Rejeki', 'phone' => '031-5556677', 'email' => 'rejeki@supplier.com', 'address' => 'Jl. Jemursari No. 15, Surabaya'],
            ['code' => 'SUPMS-002', 'branch_code' => 'BR-003', 'name' => 'UD Makmur Sentosa', 'phone' => '031-8889900', 'email' => 'sentosa@supplier.com', 'address' => 'Jl. Raya Darmo No. 30, Surabaya'],

            // Semarang
            ['code' => 'SUPMSR-001', 'branch_code' => 'BR-004', 'name' => 'CV Lestari Abadi', 'phone' => '024-2223344', 'email' => 'lestari@supplier.com', 'address' => 'Jl. Pandanaran No. 25, Semarang'],
            ['code' => 'SUPMSR-002', 'branch_code' => 'BR-004', 'name' => 'PT Agung Jaya', 'phone' => '024-5556677', 'email' => 'agung@supplier.com', 'address' => 'Jl. Gajah Mada No. 12, Semarang'],

            // Yogyakarta
            ['code' => 'SUPMY-001', 'branch_code' => 'BR-005', 'name' => 'CV Bintang Mulia', 'phone' => '0274-444555', 'email' => 'bintang@supplier.com', 'address' => 'Jl. Kaliurang No. 50, Sleman'],
            ['code' => 'SUPMY-002', 'branch_code' => 'BR-005', 'name' => 'UD Sari Makmur', 'phone' => '0274-666777', 'email' => 'sari@supplier.com', 'address' => 'Jl. Wonosari No. 18, Bantul'],
        ];

        foreach ($suppliers as $data) {
            $branch = $branchList->get($data['branch_code']);
            if (! $branch) {
                continue;
            }

            Supplier::firstOrCreate(
                ['branch_id' => $branch->id, 'code' => $data['code']],
                [
                    'name'      => $data['name'],
                    'phone'     => $data['phone'],
                    'email'     => $data['email'],
                    'address'   => $data['address'],
                    'is_active' => true,
                ]
            );
        }
    }
}

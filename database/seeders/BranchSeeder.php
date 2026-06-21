<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchSetting;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'code'    => 'BR-001',
                'name'    => 'MyFanel Bandung',
                'address' => 'Jl. Asia Afrika No. 1, Bandung',
                'phone'   => '+62 821-2834-9991',
            ],
            [
                'code'    => 'BR-002',
                'name'    => 'MyFanel Jakarta',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
                'phone'   => '+62 821-2834-9992',
            ],
            [
                'code'    => 'BR-003',
                'name'    => 'MyFanel Surabaya',
                'address' => 'Jl. Tunjungan No. 5, Surabaya',
                'phone'   => '+62 821-2834-9993',
            ],
            [
                'code'    => 'BR-004',
                'name'    => 'MyFanel Semarang Raya',
                'address' => 'Jl. Pahlawan No. 15, Semarang',
                'phone'   => '+62 821-2834-9994',
            ],
            [
                'code'    => 'BR-005',
                'name'    => 'MyFanel Yogyakarta',
                'address' => 'Jl. Malioboro No. 25, Yogyakarta',
                'phone'   => '+62 821-2834-9995',
            ],
        ];

        foreach ($branches as $data) {
            $branch = Branch::firstOrCreate(
                ['code' => $data['code']],
                $data
            );

            BranchSetting::firstOrCreate(
                ['branch_id' => $branch->id],
                [
                    'product_prefix'     => 'PRD',
                    'transaction_prefix' => 'TRX',
                    'supplier_prefix'    => 'SUP',
                    'tax_enabled'        => false,
                    'tax_rate'           => 0,
                    'discount_enabled'   => true,
                    'currency_symbol'    => 'Rp',
                ]
            );
        }
    }
}

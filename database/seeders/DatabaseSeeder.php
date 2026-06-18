<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\BranchSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Owner (Pak Jayusman) ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'owner@myfanel.com'],
            [
                'name'      => 'Pak Jayusman',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Owner,
                'is_active' => true,
            ]
        );

        // ── Contoh 2 Cabang ──────────────────────────────────────────────
        $branches = [
            [
                'code'    => 'BR-001',
                'name'    => 'MyFanel Bandung',
                'city'    => 'Bandung',
                'address' => 'Jl. Asia Afrika No. 1, Bandung',
                'phone'   => '022-1234567',
            ],
            [
                'code'    => 'BR-002',
                'name'    => 'MyFanel Jakarta',
                'city'    => 'Jakarta',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
                'phone'   => '021-9876543',
            ],
        ];

        foreach ($branches as $branchData) {
            $branch = Branch::firstOrCreate(
                ['code' => $branchData['code']],
                $branchData
            );

            BranchSetting::firstOrCreate(
                ['branch_id' => $branch->id],
                [
                    'product_prefix'     => 'PRD',
                    'transaction_prefix' => 'TRX',
                    'tax_enabled'        => false,
                    'tax_rate'           => 0,
                    'discount_enabled'   => true,
                    'currency_symbol'    => 'Rp',
                ]
            );
        }

        $branchBandung = Branch::where('code', 'BR-001')->first();

        // ── Manajer Toko BR-001 ──────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@myfanel.com'],
            [
                'name'      => 'Manajer Bandung',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Admin,
                'is_active' => true,
            ]
        );
        $admin->branches()->syncWithoutDetaching([$branchBandung->id]);

        // ── Kasir BR-001 ─────────────────────────────────────────────────
        $cashier = User::firstOrCreate(
            ['email' => 'kasir@myfanel.com'],
            [
                'name'      => 'Kasir Bandung',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Cashier,
                'is_active' => true,
            ]
        );
        $cashier->branches()->syncWithoutDetaching([$branchBandung->id]);

        // ── Pegawai Gudang BR-001 ────────────────────────────────────────
        $warehouse = User::firstOrCreate(
            ['email' => 'gudang@myfanel.com'],
            [
                'name'      => 'Gudang Bandung',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Warehouse,
                'is_active' => true,
            ]
        );
        $warehouse->branches()->syncWithoutDetaching([$branchBandung->id]);
    }
}

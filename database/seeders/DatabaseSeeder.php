<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\BranchSetting;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Owner (Pak Jayusman) ──────────────────────────────────────────
        User::firstOrCreate(
            ['username' => 'owner'],
            [
                'name'      => 'Pak Jayusman',
                'email'     => 'owner@myfanel.com',
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
        $branchJakarta = Branch::where('code', 'BR-002')->first();

        // ── Cabang 1: Bandung ────────────────────────────────────────────
        $managerBandung = User::firstOrCreate(
            ['username' => 'manager'],
            [
                'name'      => 'Manager Bandung',
                'email'     => 'manager@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Manager,
                'is_active' => true,
            ]
        );
        $managerBandung->branches()->syncWithoutDetaching([$branchBandung->id]);

        $kasirBandung = User::firstOrCreate(
            ['username' => 'kasir'],
            [
                'name'      => 'Kasir Bandung',
                'email'     => 'kasir@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Cashier,
                'is_active' => true,
            ]
        );
        $kasirBandung->branches()->syncWithoutDetaching([$branchBandung->id]);

        $gudangBandung = User::firstOrCreate(
            ['username' => 'gudang'],
            [
                'name'      => 'Gudang Bandung',
                'email'     => 'gudang@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Warehouse,
                'is_active' => true,
            ]
        );
        $gudangBandung->branches()->syncWithoutDetaching([$branchBandung->id]);

        // ── Cabang 2: Jakarta ──────────────────────────────────────────────
        $managerJakarta = User::firstOrCreate(
            ['username' => 'manager-jkt'],
            [
                'name'      => 'Manager Jakarta',
                'email'     => 'managerJ@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Manager,
                'is_active' => true,
            ]
        );
        $managerJakarta->branches()->syncWithoutDetaching([$branchJakarta->id]);

        $kasirJakarta = User::firstOrCreate(
            ['username' => 'kasir-jkt'],
            [
                'name'      => 'Kasir Jakarta',
                'email'     => 'kasirJ@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Cashier,
                'is_active' => true,
            ]
        );
        $kasirJakarta->branches()->syncWithoutDetaching([$branchJakarta->id]);

        $gudangJakarta = User::firstOrCreate(
            ['username' => 'gudang-jkt'],
            [
                'name'      => 'Gudang Jakarta',
                'email'     => 'gudangJ@myfanel.com',
                'password'  => Hash::make('password'),
                'role'      => UserRole::Warehouse,
                'is_active' => true,
            ]
        );
        $gudangJakarta->branches()->syncWithoutDetaching([$branchJakarta->id]);

        // ── Supplier per cabang ────────────────────────────────────────────
        $suppliersBandung = [
            ['code' => 'SUP-001', 'name' => 'PT Sumber Makmur', 'phone' => '022-1112233', 'email' => 'sumber@supplier.com', 'address' => 'Jl. Moh. Toha No. 10, Bandung'],
            ['code' => 'SUP-002', 'name' => 'CV Fresh Food', 'phone' => '022-4445566', 'email' => 'fresh@supplier.com', 'address' => 'Jl. Soekarno Hatta No. 5, Bandung'],
        ];

        foreach ($suppliersBandung as $data) {
            Supplier::firstOrCreate(
                ['branch_id' => $branchBandung->id, 'code' => $data['code']],
                array_merge($data, ['branch_id' => $branchBandung->id, 'is_active' => true])
            );
        }

        $suppliersJakarta = [
            ['code' => 'SUP-001', 'name' => 'PT Distribusi Nusantara', 'phone' => '021-3334455', 'email' => 'distribusi@supplier.com', 'address' => 'Jl. Gatot Subroto No. 20, Jakarta'],
            ['code' => 'SUP-002', 'name' => 'UD Berkah Jaya', 'phone' => '021-7778899', 'email' => 'berkah@supplier.com', 'address' => 'Jl. Tanah Abang No. 8, Jakarta'],
        ];

        foreach ($suppliersJakarta as $data) {
            Supplier::firstOrCreate(
                ['branch_id' => $branchJakarta->id, 'code' => $data['code']],
                array_merge($data, ['branch_id' => $branchJakarta->id, 'is_active' => true])
            );
        }
    }
}

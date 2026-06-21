<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
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

        $users = [
            // Bandung
            ['username' => 'supervisor-bdg', 'name' => 'Ahmad Faisal', 'email' => 'supervisor.bdg@myfanel.com', 'role' => UserRole::Supervisor, 'branch_code' => 'BR-001'],
            ['username' => 'kasir-bdg', 'name' => 'Dedi Kurniawan', 'email' => 'kasir.bdg@myfanel.com', 'role' => UserRole::Cashier, 'branch_code' => 'BR-001'],
            ['username' => 'gudang-bdg', 'name' => 'Siti Nurhaliza', 'email' => 'gudang.bdg@myfanel.com', 'role' => UserRole::Warehouse, 'branch_code' => 'BR-001'],

            // Jakarta
            ['username' => 'supervisor-jkt', 'name' => 'Bambang Wijaya', 'email' => 'supervisor.jkt@myfanel.com', 'role' => UserRole::Supervisor, 'branch_code' => 'BR-002'],
            ['username' => 'kasir-jkt', 'name' => 'Rudi Hartono', 'email' => 'kasir.jkt@myfanel.com', 'role' => UserRole::Cashier, 'branch_code' => 'BR-002'],
            ['username' => 'gudang-jkt', 'name' => 'Mega Wati', 'email' => 'gudang.jkt@myfanel.com', 'role' => UserRole::Warehouse, 'branch_code' => 'BR-002'],

            // Surabaya
            ['username' => 'supervisor-sby', 'name' => 'Candra Dwi Putra', 'email' => 'supervisor.sby@myfanel.com', 'role' => UserRole::Supervisor, 'branch_code' => 'BR-003'],
            ['username' => 'kasir-sby', 'name' => 'Agus Prasetyo', 'email' => 'kasir.sby@myfanel.com', 'role' => UserRole::Cashier, 'branch_code' => 'BR-003'],
            ['username' => 'gudang-sby', 'name' => 'Dewi Sartika', 'email' => 'gudang.sby@myfanel.com', 'role' => UserRole::Warehouse, 'branch_code' => 'BR-003'],

            // Semarang
            ['username' => 'supervisor-smg', 'name' => 'Eko Purwanto', 'email' => 'supervisor.smg@myfanel.com', 'role' => UserRole::Supervisor, 'branch_code' => 'BR-004'],
            ['username' => 'kasir-smg', 'name' => 'Budi Santoso', 'email' => 'kasir.smg@myfanel.com', 'role' => UserRole::Cashier, 'branch_code' => 'BR-004'],
            ['username' => 'gudang-smg', 'name' => 'Rini Susanti', 'email' => 'gudang.smg@myfanel.com', 'role' => UserRole::Warehouse, 'branch_code' => 'BR-004'],

            // Yogyakarta
            ['username' => 'supervisor-yog', 'name' => 'Gilang Ramadhan', 'email' => 'supervisor.yog@myfanel.com', 'role' => UserRole::Supervisor, 'branch_code' => 'BR-005'],
            ['username' => 'kasir-yog', 'name' => 'Cici Amalia', 'email' => 'kasir.yog@myfanel.com', 'role' => UserRole::Cashier, 'branch_code' => 'BR-005'],
            ['username' => 'gudang-yog', 'name' => 'Adi Nugroho', 'email' => 'gudang.yog@myfanel.com', 'role' => UserRole::Warehouse, 'branch_code' => 'BR-005'],
        ];

        foreach ($users as $data) {
            $branchCode = $data['branch_code'];
            unset($data['branch_code']);

            $user = User::firstOrCreate(
                ['username' => $data['username']],
                $data + ['password' => Hash::make('password'), 'is_active' => true]
            );

            $branch = \App\Models\Branch::where('code', $branchCode)->first();
            if ($branch) {
                $user->branches()->syncWithoutDetaching([$branch->id]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Branch;
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

        $branches = [
            'BR-001' => 'bdg',
            'BR-002' => 'jkt',
            'BR-003' => 'sby',
            'BR-004' => 'smg',
            'BR-005' => 'yog',
        ];

        $faker = fake('id_ID');

        foreach ([UserRole::Supervisor, UserRole::Cashier, UserRole::Warehouse] as $role) {
            $roleSlug = match ($role) {
                UserRole::Supervisor => 'supervisor',
                UserRole::Cashier => 'kasir',
                UserRole::Warehouse => 'gudang',
            };

            foreach ($branches as $branchCode => $branchSlug) {
                $username = $roleSlug . '-' . $branchSlug;

                User::firstOrCreate(
                    ['username' => $username],
                    [
                        'name'      => $faker->name(),
                        'email'     => $username . '@myfanel.com',
                        'password'  => Hash::make('password'),
                        'role'      => $role,
                        'is_active' => true,
                    ]
                )->branches()->syncWithoutDetaching(
                    [Branch::where('code', $branchCode)->first()?->id]
                );
            }
        }
    }
}

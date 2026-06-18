<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'branch_id' => Branch::factory(),
            'code'      => fake()->unique()->regexify('SUP-[0-9]{3}'),
            'name'      => fake()->company(),
            'phone'     => fake()->phoneNumber(),
            'email'     => fake()->companyEmail(),
            'address'   => fake()->address(),
            'is_active' => true,
        ];
    }
}

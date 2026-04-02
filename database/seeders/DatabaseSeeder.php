<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            BookshelvesSeeder::class,
            CategoriesSeeder::class,
            BooksSeeder::class,
            LoansSeeder::class,
            LoanDetailsSeeder::class,
            ReturnsSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(Account::factory()->count(3), 'accounts')
            ->has(Category::factory()->count(15), 'categories')
            ->create([
                'name' => 'BestSoft',
                'email' => 'besstsoft@gmail.com',
                'password' => bcrypt('123456'),
            ]);

        User::factory()->count(10)->has(Account::factory()->count(3), 'accounts')->has(Category::factory()->count(15))->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategoriesTableSeeder::class,
            UsersTableSeeder::class,
            CreateUsersWithRolesSeeder::class,
            CreateTestUserSeeder::class,
            OuvragesTableSeeder::class,
            StocksTableSeeder::class,
            VentesTableSeeder::class,
            CommentairesTableSeeder::class,
            CreateTestDataSeeder::class,
        ]);
    }
}

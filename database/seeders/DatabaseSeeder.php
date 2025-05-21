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
            // 👇 Seeder des utilisateurs avec rôles
            CreateUsersWithRolesSeeder::class,

            // 👇 Autres seeders de ton projet
            CategoriesTableSeeder::class,
            OuvragesTableSeeder::class,
            StocksTableSeeder::class,
            VentesTableSeeder::class,
            CommentairesTableSeeder::class,
        ]);
    }
}

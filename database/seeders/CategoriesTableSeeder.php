<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Catégories principales
        $categories = [
            ['nom' => 'Cuisine', 'description' => 'Livres de cuisine et de gastronomie'],
            ['nom' => 'Pâtisserie', 'description' => 'Livres de pâtisserie et desserts'],
            ['nom' => 'Vins et Spiritueux', 'description' => 'Livres sur les vins et spiritueux']
        ];

        DB::table('categories')->insert([
            ['nom' => 'Roman'],
            ['nom' => 'Science-fiction'],
            ['nom' => 'Policier'],
            ['nom' => 'Biographie'],
            ['nom' => 'Histoire'],
            ['nom' => 'Fantastique'],
            ['nom' => 'Jeunesse'],
            ['nom' => 'Bande dessinée'],
            ['nom' => 'Manga'],
            ['nom' => 'Poésie'],
            ['nom' => 'Théâtre'],
            ['nom' => 'Essai'],
            ['nom' => 'Art'],
            ['nom' => 'Cuisine'],
            ['nom' => 'Sport'],
            ['nom' => 'Voyage'],
            ['nom' => 'Sciences'],
            ['nom' => 'Informatique'],
            ['nom' => 'Économie'],
            ['nom' => 'Philosophie']
        ]);

        foreach ($categories as $categorie) {
            $id = DB::table('categories')->insertGetId([
                'nom' => $categorie['nom'],
                'description' => $categorie['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Sous-catégories
            if ($categorie['nom'] === 'Cuisine') {
                DB::table('categories')->insert([
                    ['nom' => 'Cuisine Française', 'description' => 'Spécialités françaises', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()],
                    ['nom' => 'Cuisine Italienne', 'description' => 'Spécialités italiennes', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()],
                    ['nom' => 'Cuisine Asiatique', 'description' => 'Spécialités asiatiques', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()]
                ]);
            }
            elseif ($categorie['nom'] === 'Pâtisserie') {
                DB::table('categories')->insert([
                    ['nom' => 'Gâteaux', 'description' => 'Recettes de gâteaux', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()],
                    ['nom' => 'Biscuits', 'description' => 'Recettes de biscuits', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()],
                    ['nom' => 'Chocolats', 'description' => 'Recettes de chocolats', 'parent_id' => $id, 'created_at' => now(), 'updated_at' => now()]
                ]);
            }
        }
    }
}

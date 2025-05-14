<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OuvragesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DB::table('categories')->get();
        
        $ouvrages = [
            [
                'titre' => 'La Cuisine Française Facile',
                'description' => 'Découvrez les secrets de la cuisine française',
                'auteur' => 'Jean Dupont',
                'editeur' => 'Editions Culinaires',
                'isbn' => '978-2-1234-5680-1',
                'prix' => 29.99,
                'date_publication' => '2024-01-15',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Cuisine')->first()->id
            ],
            [
                'titre' => 'Le Seigneur des Anneaux : La Communauté de l\'Anneau',
                'description' => 'Le premier tome de la célèbre trilogie de Tolkien',
                'auteur' => 'J.R.R. Tolkien',
                'editeur' => 'Christian Bourgois',
                'isbn' => '978-2-2670-1183-7',
                'prix' => 24.90,
                'date_publication' => '1954-07-29',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Fantastique')->first()->id
            ],
            [
                'titre' => 'L\'Art de la Programmation en Python',
                'description' => 'Guide complet pour maîtriser Python',
                'auteur' => 'Sarah Johnson',
                'editeur' => 'Editions Tech',
                'isbn' => '978-2-8765-4321-0',
                'prix' => 45.00,
                'date_publication' => '2023-06-15',
                'niveau' => 'amateur',
                'categorie_id' => $categories->where('nom', 'Informatique')->first()->id
            ],
            [
                'titre' => 'One Piece Tome 1',
                'description' => 'Le début de l\'aventure de Luffy',
                'auteur' => 'Eiichiro Oda',
                'editeur' => 'Glénat',
                'isbn' => '978-2-7234-4757-9',
                'prix' => 6.90,
                'date_publication' => '1997-07-22',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Manga')->first()->id
            ],
            [
                'titre' => 'Les Fleurs du Mal',
                'description' => 'Recueil de poèmes de Charles Baudelaire',
                'auteur' => 'Charles Baudelaire',
                'editeur' => 'Gallimard',
                'isbn' => '978-2-0701-2947-8',
                'prix' => 19.90,
                'date_publication' => '1857-06-25',
                'niveau' => 'chef',
                'categorie_id' => $categories->where('nom', 'Poésie')->first()->id
            ],
            [
                'titre' => 'Le Guide du Voyageur Galactique',
                'description' => 'Un roman de science-fiction humoristique',
                'auteur' => 'Douglas Adams',
                'editeur' => 'Denoël',
                'isbn' => '978-2-2070-3674-1',
                'prix' => 22.90,
                'date_publication' => '1979-10-12',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Science-fiction')->first()->id
            ],
            [
                'titre' => 'Histoire de l\'Art',
                'description' => 'Panorama complet de l\'histoire de l\'art',
                'auteur' => 'Ernst Gombrich',
                'editeur' => 'Phaidon',
                'isbn' => '978-2-8766-5432-1',
                'prix' => 49.95,
                'date_publication' => '2023-01-10',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Art')->first()->id
            ],
            [
                'titre' => 'Le Sport pour les Nuls',
                'description' => 'Guide complet pour débuter en sport',
                'auteur' => 'Marc Sportif',
                'editeur' => 'First',
                'isbn' => '978-2-7654-3210-9',
                'prix' => 22.95,
                'date_publication' => '2024-01-05',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Sport')->first()->id
            ],
            [
                'titre' => 'Méditer Jour Après Jour',
                'description' => 'Guide pratique de méditation',
                'auteur' => 'Christophe André',
                'editeur' => 'L\'Iconoclaste',
                'isbn' => '978-2-3456-7890-1',
                'prix' => 18.90,
                'date_publication' => '2023-09-15',
                'niveau' => 'débutant',
                'categorie_id' => $categories->where('nom', 'Philosophie')->first()->id
            ],
            [
                'titre' => 'Les Secrets de l\'Économie',
                'description' => 'Comprendre l\'économie moderne',
                'auteur' => 'Thomas Piketty',
                'editeur' => 'Seuil',
                'isbn' => '978-2-0123-4567-8',
                'prix' => 25.00,
                'date_publication' => '2023-11-30',
                'niveau' => 'amateur',
                'categorie_id' => $categories->where('nom', 'Économie')->first()->id
            ]
        ];

        foreach ($ouvrages as $ouvrage) {
            // Vérifie si l'ISBN existe déjà
            $existingOuvrage = DB::table('ouvrages')
                ->where('isbn', $ouvrage['isbn'])
                ->first();
            
            if (!$existingOuvrage) {
                DB::table('ouvrages')->insert(array_merge($ouvrage, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }
}

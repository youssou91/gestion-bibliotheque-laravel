<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Insérer les catégories principales
        $categories = [
            ['nom' => 'Romans', 'description' => 'Tous types de romans', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Sciences', 'description' => 'Livres scientifiques', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Informatique', 'description' => 'Livres sur l\'informatique et la programmation', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        // Récupérer les IDs des catégories
        $categoriesIds = DB::table('categories')->pluck('id');

        // Insérer les sous-catégories
        $sousCategories = [
            ['nom' => 'Science-Fiction', 'description' => 'Romans de science-fiction', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Policier', 'description' => 'Romans policiers et thrillers', 'parent_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Physique', 'description' => 'Livres de physique', 'parent_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Programmation', 'description' => 'Livres sur la programmation', 'parent_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($sousCategories);

        // Insérer les ouvrages
        $ouvrages = [];
        $ouvrages[] = [
            'titre' => 'Dune',
            'auteur' => 'Frank Herbert',
            'description' => "L'histoire d'une planète désertique et d'une famille noble",
            'editeur' => 'Chilton Books',
            'isbn' => '9780441172719',
            'prix' => 24.99,
            'date_publication' => '1965-08-01',
            'niveau' => 'Avancé',
            'photo' => 'img/dune.jpg',
            'categorie_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ];
        $ouvrages[] = [
            'titre' => 'Clean Code',
            'auteur' => 'Robert C. Martin',
            'description' => 'Guide pour écrire du code propre et maintenable',
            'editeur' => 'Prentice Hall',
            'isbn' => '9780132350884',
            'prix' => 39.99,
            'date_publication' => '2008-08-01',
            'niveau' => 'Intermédiaire',
            'photo' => 'img/clean_code.jpg',
            'categorie_id' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ];
        $ouvrages[] = [
            'titre' => 'Le Nom de la Rose',
            'auteur' => 'Umberto Eco',
            'description' => 'Une enquête dans une abbaye médiévale',
            'editeur' => 'Grasset',
            'isbn' => '9782253033134',
            'prix' => 22.99,
            'date_publication' => '1980-01-01',
            'niveau' => 'Débutant',
            'photo' => 'img/le_nom_de_la_rose.jpg',
            'categorie_id' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ];
        DB::table('ouvrages')->insert($ouvrages);

        // Insérer les stocks
        $stocks = [];
        $ouvragesIds = DB::table('ouvrages')->pluck('id');
        foreach ($ouvragesIds as $ouvrageId) {
            $stocks[] = [
                'ouvrage_id' => $ouvrageId,
                'quantite' => rand(5, 20),
                'prix_achat' => $prix_achat = rand(10, 30),
                'prix_vente' => $prix_achat * 1.4, // 40% de marge
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        DB::table('stocks')->insert($stocks);

        // Création des commentaires
        $commentaires = [
            [
                'contenu' => 'Un chef-d\'œuvre absolu ! L\'histoire est captivante du début à la fin. Les personnages sont très bien développés et l\'intrigue est pleine de rebondissements. Je recommande vivement ce livre à tous les amateurs de science-fiction.',
                'utilisateur_id' => 1,
                'ouvrage_id' => 1,
                'note' => 5,
                'statut' => 'approuve',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'contenu' => 'Ce livre est une excellente ressource pour tout développeur. Les principes de clean code sont bien expliqués avec des exemples concrets. J\'ai beaucoup appris en le lisant.',
                'utilisateur_id' => 2,
                'ouvrage_id' => 2,
                'note' => 4,
                'statut' => 'approuve',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'contenu' => 'Une intrigue médiévale fascinante ! Le mélange de mystère et d\'histoire est parfaitement dosé. La façon dont l\'auteur décrit l\'ambiance de l\'abbaye est remarquable.',
                'utilisateur_id' => 3,
                'ouvrage_id' => 3,
                'note' => 5,
                'statut' => 'en attente',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('commentaires')->insert($commentaires);

        // Insérer quelques ventes
        $ventes = [
            [
                'utilisateur_id' => 2,
                'date_vente' => now(),
                'montant_total' => 87.97,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        $venteId = DB::table('ventes')->insertGetId($ventes[0]);

        // Insérer les lignes de vente
        $ligneVentes = [
            [
                'vente_id' => $venteId,
                'ouvrage_id' => 1,
                'utilisateur_id' => 2,
                'quantite' => 2,
                'prix_unitaire' => 24.99,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'vente_id' => $venteId,
                'ouvrage_id' => 2,
                'utilisateur_id' => 2,
                'quantite' => 1,
                'prix_unitaire' => 39.99,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        DB::table('ligne_ventes')->insert($ligneVentes);
    }
}

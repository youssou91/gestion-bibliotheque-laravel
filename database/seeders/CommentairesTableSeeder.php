<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CommentairesTableSeeder extends Seeder
{
    public function run(): void
    {
        $ouvrages = DB::table('ouvrages')->get();
        $users = DB::table('utilisateurs')->get();

        $commentaires_types = [
            'Excellent livre, je le recommande vivement !',
            'Une lecture passionnante du début à la fin.',
            'Intéressant mais un peu difficile à comprendre par moments.',
            'Bon livre dans l\'ensemble, quelques longueurs cependant.',
            'Parfait pour débuter dans ce domaine.',
            'Un classique incontournable.',
            'Les illustrations sont magnifiques.',
            'Le style d\'écriture est très agréable.',
            'Un peu déçu par la fin.',
            'Rapport qualité-prix excellent.',
            'Idéal pour approfondir ses connaissances.',
            'Belle découverte !',
            'Je l\'ai lu d\'une traite.',
            'Un peu cher pour ce que c\'est.',
            'Très bien expliqué, facile à comprendre.'
        ];

        foreach ($ouvrages as $ouvrage) {
            $nbCommentaires = rand(2, 8); // Plus de commentaires par ouvrage
            
            for ($i = 0; $i < $nbCommentaires; $i++) {
                $user = $users->random();
                $date = now()->subDays(rand(1, 365)); // Commentaires sur la dernière année
                
                // Combine 1 à 3 commentaires types pour plus de variété
                $nbCommentairesTypes = rand(1, 3);
                $commentairesSelectionnes = array_rand($commentaires_types, $nbCommentairesTypes);
                if (!is_array($commentairesSelectionnes)) {
                    $commentairesSelectionnes = [$commentairesSelectionnes];
                }
                
                $contenu = '';
                foreach ($commentairesSelectionnes as $index) {
                    $contenu .= $commentaires_types[$index] . ' ';
                }
                
                // Ajoute une appréciation spécifique au livre
                $contenu .= "\nConcernant \"" . $ouvrage->titre . "\", ";
                $appreciations = [
                    "c'est une lecture enrichissante.",
                    "je le conseille aux amateurs du genre.",
                    "ça vaut vraiment le détour."
                ];
                $appreciation = $appreciations[array_rand($appreciations)];
                $contenu .= $appreciation;
                               
                DB::table('commentaires')->insert([
                    'contenu' => trim($contenu),
                    'note' => rand(3, 5), 
                    'utilisateur_id' => $user->id,
                    'ouvrage_id' => $ouvrage->id,
                    'statut' => rand(0, 10) > 2 ? 'approuve' : 'en_attente', 
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }
    }
}

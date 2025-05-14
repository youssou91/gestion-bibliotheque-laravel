<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class VentesTableSeeder extends Seeder
{
    public function run(): void
    {
        $ouvrages = DB::table('ouvrages')->get();
        $users = DB::table('utilisateurs')->get();

        // Générer des ventes sur les 6 derniers mois
        $debut = now()->subMonths(6);
        $fin = now();

        // Chaque utilisateur aura entre 0 et 5 ventes
        foreach ($users as $user) {
            $nbVentes = rand(0, 5);
            
            for ($i = 0; $i < $nbVentes; $i++) {
                // Date aléatoire entre il y a 6 mois et maintenant
                $date = Date::createFromTimestamp(rand($debut->timestamp, $fin->timestamp));
                
                // Créer la vente principale
                $vente_id = DB::table('ventes')->insertGetId([
                    'utilisateur_id' => $user->id,
                    'montant_total' => 0, // sera mis à jour après l'ajout des lignes
                    'date_vente' => $date,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
                
                // Ajouter entre 1 et 3 ouvrages à la vente
                $nbOuvrages = rand(1, 3);
                $montant_total = 0;
                
                // Sélectionner des ouvrages aléatoires
                $ouvrages_selectionnes = $ouvrages->random($nbOuvrages);
                
                foreach ($ouvrages_selectionnes as $ouvrage) {
                    // Quantité vendue entre 1 et 3
                    $quantite = rand(1, 3);
                    
                    // Prix unitaire basé sur le prix de l'ouvrage
                    $prix_unitaire = $ouvrage->prix;
                    
                    // Ajouter la ligne de vente
                    DB::table('ligne_ventes')->insert([
                        'vente_id' => $vente_id,
                        'ouvrage_id' => $ouvrage->id,
                        'utilisateur_id' => $user->id,
                        'quantite' => $quantite,
                        'prix_unitaire' => $prix_unitaire,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                    
                    // Mettre à jour le stock
                    DB::table('stocks')
                        ->where('ouvrage_id', $ouvrage->id)
                        ->update([
                            'quantite' => DB::raw("quantite - $quantite"),
                            'derniere_sortie' => $date
                        ]);
                    
                    $montant_total += $prix_unitaire * $quantite;
                }
                
                // Mettre à jour le montant total de la vente
                DB::table('ventes')
                    ->where('id', $vente_id)
                    ->update([
                        'montant_total' => $montant_total
                    ]);
            }
        }
    }
}

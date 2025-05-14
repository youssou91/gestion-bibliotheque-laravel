<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $ouvrages = DB::table('ouvrages')->get();

        foreach ($ouvrages as $ouvrage) {
            // Génère une quantité aléatoire entre 0 et 50
            $quantite = rand(0, 50);
            
            // Définit un seuil d'alerte en fonction de la quantité
            $seuil_alerte = max(5, floor($quantite * 0.2));
            
            // Génère un emplacement aléatoire
            $rayons = ['A', 'B', 'C', 'D', 'E'];
            $etages = [1, 2, 3, 4];
            $emplacement = $rayons[array_rand($rayons)] . '-' . $etages[array_rand($etages)] . '-' . str_pad(rand(1, 20), 2, '0', STR_PAD_LEFT);
            
            DB::table('stocks')->insert([
                'ouvrage_id' => $ouvrage->id,
                'quantite' => $quantite,
                'seuil_alerte' => $seuil_alerte,
                'emplacement' => $emplacement,
                'derniere_entree' => now()->subDays(rand(1, 60)),
                'derniere_sortie' => now()->subDays(rand(1, 30)),
                'prix_achat' => $ouvrage->prix * 0.6, // 60% du prix de vente
                'prix_vente' => $ouvrage->prix,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

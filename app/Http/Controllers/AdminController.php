<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function maintenirSite()
    {
        return view('maintenir_site');
    }

    public function gererUtilisateurs()
    {
        return view('gerer_utilisateurs');
    }

    public function gererCatalogue()
    {
        return view('gerer_catalogue');
    }

    public function valider($id)
    {
        $reservation = Reservation::findOrFail($id);
        $ouvrage = $reservation->ouvrage;

        if (!$ouvrage->stock || $ouvrage->stock->quantite <= 0) {
            return back()->with('error', 'Stock insuffisant pour valider la réservation.');
        }

        // Création de l’emprunt
        Emprunt::create([
            'utilisateur_id' => $reservation->utilisateur_id,
            'ouvrage_id' => $reservation->ouvrage_id,
            'date_emprunt' => now(),
            'date_retour' => now()->addDays(7), // ou durée personnalisée
            'statut' => 'en_cours',
        ]);

        // Décrémente le stock
        $ouvrage->stock->decrement('quantite');

        // Met à jour la réservation
        $reservation->update([
            'statut' => 'validee',
        ]);

        return back()->with('success', 'Réservation validée et emprunt enregistré.');
    }
}

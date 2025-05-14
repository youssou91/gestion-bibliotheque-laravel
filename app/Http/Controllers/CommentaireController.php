<?php

namespace App\Http\Controllers;

use App\Models\Commentaires;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{

    /**
     * Affiche la page de validation des commentaires avec statistiques.
     */
    public function index()
    {
        // Nombre de commentaires en attente
        $pendingCount = Commentaires::where('statut', 'en_attente')->count();

        // Nombre de commentaires approuvés et rejetés sur les 30 derniers jours
        $approuve30 = Commentaires::where('statut', 'approuve')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        // Nombre de commentaires rejetés sur les 30 derniers jours
        $rejete30 = Commentaires::where('statut', 'rejete')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        // Charger tous les commentaires avec leurs relations
        $comments = Commentaires::with(['utilisateur', 'ouvrage'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Rendre la vue en passant les données
        return view('validate_comments', compact(
            'pendingCount',
            'approuve30',
            'rejete30',
            'comments'
        ));
    }

    /**
     * Récupère un commentaire en JSON pour le modal
     */
    public function show(Commentaires $commentaire)
    {
        $commentaire->load(['utilisateur', 'ouvrage']);
        return response()->json($commentaire);
    }

    /**
     * Approuve un commentaire.
     */
    public function approuve(Commentaires $commentaire)
    {
        $commentaire->update(['statut' => 'approuve']);
        return back()->with('success', 'Commentaire approuvé');
    }

    /**
     * Rejette un commentaire.
     */
    public function reject(Commentaires $commentaire)
    {
        $commentaire->update(['statut' => 'rejete']);
        return back()->with('success', 'Commentaire rejeté');
    }
}

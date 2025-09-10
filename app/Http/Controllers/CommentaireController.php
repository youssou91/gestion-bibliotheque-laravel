<?php

namespace App\Http\Controllers;

use App\Models\Commentaires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['approuve', 'reject']);
    }

    /**
     * Affiche la page de validation des commentaires avec statistiques.
     */
    public function index()
    {
        // Nombre de commentaires en attente
        $pendingCount = Commentaires::where('statut', 'en_attente')->count();

        // Nombre de commentaires approuvés et rejetés sur les 30 derniers jours
        $approuve30 = Commentaires::where('statut', 'approuve')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
        // Nombre total de commentaires approuvés 
        $approuve = Commentaires::where('statut', 'approuve')->count();
        // Nombre de commentaires rejetés sur les 30 derniers jours
        $rejete30 = Commentaires::where('statut', 'rejete')
            ->where('updated_at', '>=', now()->subDays(30))
            ->count();
        // Nombre total de commentaires rejetés
        $rejete = Commentaires::where('statut', 'rejete')->count();
        // Charger tous les commentaires avec leurs relations
        $comments = Commentaires::with(['utilisateur', 'ouvrage'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Rendre la vue en passant les données
        return view('admin.validate_comments', compact(
            'pendingCount',
            'approuve30',
            'approuve',
            'rejete',
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
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire approuvé avec succès!',
                'comment' => $commentaire->load('utilisateur')
            ]);
        }
        
        return back()->with('success', 'Commentaire approuvé avec succès!');
    }

    /**
     * Rejette un commentaire.
     */
    public function reject(Commentaires $commentaire)
    {
        $commentaire->update(['statut' => 'rejete']);
        
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Commentaire rejeté avec succès!',
                'comment' => $commentaire->load('utilisateur')
            ]);
        }
        
        return back()->with('success', 'Commentaire rejeté avec succès!');
    }

    /**
     * Enregistrer un nouveau commentaire pour un ouvrage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string|max:1000',
            'note' => 'required|integer|between:1,5',
        ]);

        Commentaires::create([
            'contenu' => $request->commentaire,
            'note' => $request->note,
            'utilisateur_id' => Auth::id(),
            'ouvrage_id' => $id,
        ]);

        return redirect()->back()->with('success', 'Merci pour votre commentaire !');
    }
}

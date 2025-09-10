<?php

namespace App\Http\Controllers;

use App\Models\Amende;
use App\Models\Emprunt;
use App\Models\Ouvrages;
use App\Models\Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmpruntController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }
    public function store(Request $request)
    {
        $request->validate([
            'livre_id' => 'required|exists:ouvrages,id',
            'utilisateur_id' => 'sometimes|required|exists:utilisateurs,id',
        ]);

        $userId = $request->has('utilisateur_id') ? $request->utilisateur_id : auth()->id();
        
        // Vérification emprunt existant
        $empruntEnCours = Emprunt::where('utilisateur_id', $userId)
            ->where('ouvrage_id', $request->livre_id)
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->exists();

        if ($empruntEnCours) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà un emprunt en cours pour ce livre.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Vous avez déjà un emprunt en cours pour ce livre.');
        }

        $livre = Ouvrages::with('stock')->findOrFail($request->livre_id);

        // Vérification disponibilité
        if ($livre->statut !== 'disponible' || ($livre->stock && $livre->stock->quantite <= 0)) {
            return redirect()->back()->with('error', 'Ce livre n\'est pas disponible pour emprunt.');
        }

        $dateRetour = now()->addDays(14);

        // Création de l'emprunt
        $emprunt = Emprunt::create([
            'utilisateur_id' => $userId,
            'ouvrage_id' => $request->livre_id,
            'date_emprunt' => now(),
            'date_retour' => $dateRetour,
            'statut' => 'en_cours',
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Emprunt créé avec succès',
                'emprunt' => $emprunt->load(['utilisateur', 'ouvrage'])
            ], 201);
        }

        // Mise à jour statut ouvrage
        $livre->update([
            'statut' => 'emprunté',
            'date_disponibilite' => $dateRetour
        ]);

        // Mise à jour stock si applicable
        if ($livre->stock) {
            $livre->stock->decrement('quantite');
            $this->updateStockStatus($livre->stock);
        }

        return redirect()->back()->with('success', 'Livre emprunté avec succès. Date de retour: '.$dateRetour->format('d/m/Y'));
    }

    public function retour($id)
    {
        $emprunt = Emprunt::with(['ouvrage', 'ouvrage.stock'])->findOrFail($id);
        $today = now();
        $retard = $today->greaterThan($emprunt->date_retour);
        $amende = 0;

        // Gestion des amendes pour retard
        if ($retard) {
            $jours = $today->diffInDays($emprunt->date_retour);
            $amende = $jours * 1.50;

            Amende::create([
                'utilisateur_id' => $emprunt->utilisateur_id,
                'ouvrage_id' => $emprunt->ouvrage_id,
                'emprunt_id' => $emprunt->id,
                'montant' => $amende,
                'date_amende' => $today,
                'est_payee' => false,
                'motif' => 'Retard de ' . $jours . ' jour(s) sur le retour'
            ]);
        }

        // Mise à jour emprunt
        $emprunt->update([
            'date_effective_retour' => $today,
            'statut' => 'retourne',
            'amende' => $amende
        ]);

        // Mise à jour ouvrage
        $emprunt->ouvrage->update([
            'statut' => 'disponible',
            'date_disponibilite' => null
        ]);

        // Mise à jour stock
        if ($emprunt->ouvrage->stock) {
            $emprunt->ouvrage->stock->increment('quantite');
            $this->updateStockStatus($emprunt->ouvrage->stock);
        }

        return back()->with('success', 'Livre retourné. ' .
            ($retard ? 'Amende : $' . number_format($amende, 2) : 'Pas d\'amende à payer'));
    }

    public function index()
    {
        $emprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        return view('Suivie_ventes', compact('emprunts'));
    }

    public function nbEmprunts()
    {
        return Emprunt::where('utilisateur_id', auth()->id())
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->count();
    }

    public function mesEmprunts()
    {
        $this->verifierRetards();

        $utilisateurId = auth()->id();

        $empruntsEnCours = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->whereIn('statut', ['en_cours', 'en_retard'])
            ->orderBy('date_emprunt', 'desc')
            ->get();

        $empruntsHistorique = Emprunt::with('ouvrage')
            ->where('utilisateur_id', $utilisateurId)
            ->whereNotIn('statut', ['en_cours', 'en_retard'])
            ->orderBy('date_retour', 'desc')
            ->paginate(10);

        return view('frontOffice.emprunts', compact('empruntsEnCours', 'empruntsHistorique'));
    }

    private function verifierRetards()
    {
        Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour', '<', now())
            ->update(['statut' => 'en_retard']);
    }

    public function favoris()
    {
        $favoris = Auth::user()->favoris()->with('ouvrage')->get();
        return view('frontOffice.favoris', compact('favoris'));
    }

    public function adminDashboard()
    {
        // Statistiques principales
        $stats = [
            'empruntsMois' => Emprunt::whereMonth('date_emprunt', now()->month)->count(),
            'empruntsSemaine' => Emprunt::whereBetween('date_emprunt', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'retards' => Emprunt::where('statut', 'en_retard')->count(),
            'retournes' => Emprunt::where('statut', 'retourne')->count()
        ];

        // Top ouvrage
        $topOuvrage = Ouvrages::withCount('emprunts')
            ->orderByDesc('emprunts_count')
            ->first();

        // Données pour les graphiques
        $emprunts30Jours = Emprunt::selectRaw('DATE(date_emprunt) as date, COUNT(*) as count')
            ->whereBetween('date_emprunt', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Derniers emprunts
        $lastEmprunts = Emprunt::with('ouvrage', 'utilisateur')
            ->orderByDesc('date_emprunt')
            ->take(10)
            ->get();

        return view('admin.dashboard', array_merge($stats, [
            'topOuvrage' => $topOuvrage->titre ?? 'Aucun',
            'topEmprunts' => $topOuvrage->emprunts_count ?? 0,
            'empruntsData' => $emprunts30Jours->pluck('count'),
            'empruntsDates' => $emprunts30Jours->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m')),
            'lastEmprunts' => $lastEmprunts,
            'catLabels' => ['En cours', 'En retard', 'Retournés'],
            'catData' => [
                Emprunt::where('statut', 'en_cours')->count(),
                $stats['retards'],
                $stats['retournes']
            ]
        ]));
    }

    // Met à jour le statut du stock en fonction de la quantité
    protected function updateStockStatus($stock)
    {
        if ($stock->quantite == 0) {
            $status = 'Rupture';
        } elseif ($stock->quantite <= 5) {
            $status = 'Stock faible';
        } else {
            $status = 'En stock';
        }

        $stock->update(['statut' => $status]);
    }
}
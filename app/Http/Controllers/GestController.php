<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Ligne_ventes;
use App\Models\Ouvrages;
use App\Models\Ventes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestController extends Controller
{
    public function index()
    {
        // Statistiques de base
        $ventesMois = Ventes::whereMonth('created_at', now()->month)->count();
        $CA = Ligne_ventes::sum(DB::raw('quantite * prix_unitaire'));
        $panierMoyen = $ventesMois > 0 ? $CA / $ventesMois : 0;

        // Top livre
        $topLivre = Ligne_ventes::select('ouvrage_id', DB::raw('SUM(quantite) as total'))
            ->with('ouvrage')
            ->groupBy('ouvrage_id')
            ->orderByDesc('total')
            ->first();

        $topLivreTitle = $topLivre->ouvrage->titre ?? 'Aucun';
        $topVentes = $topLivre->total ?? 0;

        // Données pour les graphiques (30 derniers jours)
        $startDate = now()->subDays(29)->startOfDay();

        // Graphique linéaire (ventes par jour)
        $dailyData = Ventes::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $dailyDates = collect(range(0, 29))
            ->map(fn($i) => now()->subDays(29 - $i)->format('d/m'))
            ->toArray();

        $dailyCounts = collect(range(0, 29))
            ->map(fn($i) => $dailyData[now()->subDays(29 - $i)->format('Y-m-d')] ?? 0)
            ->toArray();

        // Graphique circulaire (répartition par catégorie)
        $categories = Ligne_ventes::select('ouvrages.categorie_id', DB::raw('SUM(ligne_ventes.quantite) as total'))
            ->join('ouvrages', 'ligne_ventes.ouvrage_id', '=', 'ouvrages.id')
            ->where('ligne_ventes.created_at', '>=', $startDate)
            ->groupBy('ouvrages.categorie_id')
            ->get()
            ->pluck('total', 'categorie')
            ->toArray();

        $catLabels = array_keys($categories);
        $catData = array_values($categories);

        // Dernières transactions
        $lastLines = Ligne_ventes::with('ouvrage')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('Suivie_ventes', compact(
            'ventesMois',
            'CA',
            'panierMoyen',
            'topLivreTitle',
            'topVentes',
            'dailyDates',
            'dailyCounts',
            'catLabels',
            'catData',
            'lastLines'
        ));
    }
    private function verifierRetards()
    {
        Emprunt::where('statut', 'en_cours')
            ->whereDate('date_retour', '<', now())
            ->where('statut', '!=', 'en_retard') 
            ->update(['statut' => 'en_retard']);
    }
    private function getDashboardData()
    {
        $this->verifierRetards();  // Appelle la fonction pour vérifier les retards

        // Statistiques principales
        $empruntsMois = Emprunt::whereMonth('created_at', now()->month)->count();
        $empruntsSemaine = Emprunt::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $retards = Emprunt::where('statut', 'en_cours')
            ->where('date_retour', '<', now())
            ->count();

        // Top ouvrage
        $topOuvrage = Ouvrages::withCount('emprunts')
            ->orderByDesc('emprunts_count')
            ->first();

        // Données pour les graphiques (30 derniers jours)
        $startDate = now()->subDays(29)->startOfDay();

        // Graphique linéaire (emprunts par jour)
        $dailyData = Emprunt::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $dailyDates = collect(range(0, 29))
            ->map(fn($i) => now()->subDays(29 - $i)->format('d/m'))
            ->toArray();

        $dailyCounts = collect(range(0, 29))
            ->map(fn($i) => $dailyData[now()->subDays(29 - $i)->format('Y-m-d')] ?? 0)
            ->toArray();

        // Graphique circulaire (répartition par statut)
        $statuts = Emprunt::selectRaw('statut, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('statut')
            ->pluck('count', 'statut')
            ->toArray();

        $statutLabels = [
            'en_cours' => 'En cours',
            'retourne' => 'Retournés',
            'en_retard' => 'En retard'
        ];

        $statutData = [];
        foreach ($statutLabels as $key => $label) {
            $statutData[$label] = $statuts[$key] ?? 0;
        }

        // Derniers emprunts
        $lastEmprunts = Emprunt::with(['ouvrage', 'utilisateur'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return [
            'empruntsMois' => $empruntsMois,
            'empruntsSemaine' => $empruntsSemaine,
            'retards' => $retards,
            'topOuvrage' => $topOuvrage->titre ?? 'Aucun',
            'topEmprunts' => $topOuvrage->emprunts_count ?? 0,
            'dailyDates' => $dailyDates,
            'dailyData' => $dailyCounts,
            'statutLabels' => array_keys($statutData),
            'statutData' => array_values($statutData),
            'lastEmprunts' => $lastEmprunts
        ];
    }

    // public function index()
    // {
    //     return view('Suivie_ventes', $this->getDashboardData());
    // }

    public function adminDashboard()
    {
        return view('admin.dashboard', $this->getDashboardData());
    }

    public function show(Ligne_ventes $lignevente)
    {
        $lignevente->load(['vente', 'ouvrage', 'utilisateur']);
        return response()->json($lignevente);
    }
    public function gererCatalogue()
    {
        return view('gerer_catalogue');
    }

    public function suivreVentes()
    {
        return view('Suivie_ventes');
    }
}
